<?php

namespace App\Http\Controllers;

use App\Models\SeniorCitizen;
use App\Models\FamilyMember;
use App\Models\AuditLog;
use App\Services\FilterService;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;

class SeniorCitizenController extends Controller
{
    /**
     * Display a listing of the senior citizens with advanced filtering.
     * 
     * Uses the FilterService for clean, reusable filtering logic.
     * Supports: text search, select filters, boolean filters, classification, age ranges.
     */
    public function index(Request $request)
    {
        // If user types a plain number in the general search box (e.g. "71"),
        // interpret it as an exact age unless an explicit age filter is already chosen.
        if (
            $request->filled('search')
            && !$request->filled('age_exact')
            && !$request->filled('age_range')
            && is_numeric(trim((string) $request->input('search')))
        ) {
            $n = (int) trim((string) $request->input('search'));
            if ($n >= 60 && $n <= 120) {
                $request->merge([
                    'age_exact' => (string) $n,
                    'search' => null,
                ]);
            }
        }

        // Initialize the query and filter service
        $query = SeniorCitizen::withoutTrashed();
        $filterService = new FilterService($query, $request);

        // Register filters using the service
        $filterService
            ->textSearch('search', [
                'firstname', 'middlename', 'lastname', 'fullname',
                'osca_id', 'contact_number', 'address'
            ], [
                'label' => 'Search',
                'placeholder' => 'Name, OSCA ID, contact, or address...',
                'icon' => '🔍',
            ])
            ->select('sex', 'sex', [
                'Male' => 'Male',
                'Female' => 'Female',
                'Other' => 'Other',
            ], [
                'label' => 'Sex',
                'icon' => '👥',
            ])
            ->select('barangay', 'barangay', 
                array_combine(
                    \App\Constants\Barangay::list(), 
                    \App\Constants\Barangay::list()
                ), [
                'label' => 'Barangay',
                'icon' => '📍',
            ])
            ->boolean('social_pension', 'social_pension', [
                'label' => 'Social Pension',
                'icon' => '💰',
            ])
            ->select('pension_type', 'pension_type', [
                'sss' => 'SSS',
                'gsis' => 'GSIS',
                'pvao' => 'PVAO',
            ], [
                'label' => 'Pension Type',
                'icon' => '🎓',
            ])
            ->select('classification', null, [
                'pensioners' => '💼 Pensioners',
                'indigent' => '🤝 Indigent',
                'with_disability' => '♿ Persons with Disability',
                'bedridden' => '🛏️ Bedridden',
                'critical_illness' => '⚕️ Critical Illness',
            ], [
                'label' => 'Classification',
                'icon' => '📋',
            ])
            ->custom('classification', function($q, $value) {
                switch ($value) {
                    case 'pensioners':
                        $q->where('is_pensioner', true);
                        break;
                    case 'indigent':
                        $q->where('is_indigent', true);
                        break;
                    case 'with_disability':
                        $q->where('with_disability', true);
                        break;
                    case 'bedridden':
                        $q->where('bedridden', true);
                        break;
                    case 'critical_illness':
                        $q->where('with_critical_illness', true);
                        break;
                }
            }, ['label' => 'Classification', 'icon' => '📋'])
            ->custom('age_exact', function($q, $value) {
                if (is_numeric($value) && (int)$value >= 60) {
                    $q->where('age', (int)$value);
                }
            }, ['label' => 'Exact Age', 'icon' => '🎂'])
            ->custom('age_range', function($q, $value, $req) {
                if ($req->filled('age_exact')) return; // exact age takes precedence
                $value = trim($value ?? '');
                if ($value === '') return;
                if (preg_match('/^(\d+)-(\d+)$/', $value, $m)) {
                    $q->whereBetween('age', [(int)$m[1], (int)$m[2]]);
                } elseif ($value === '80+') {
                    $q->where('age', '>=', 80);
                }
            }, ['label' => 'Age Range', 'icon' => '🎂']);

        // Get the filtered query
        $query = $filterService->getQuery();

        // Apply sorting
        $sort = $request->query('sort', 'name_asc');
        $query = $this->applySort($query, $sort);
        $seniorCitizens = $query->paginate(15)->appends($request->query());

        // Pass filter information to view
        return view('senior-citizens.index', [
            'seniorCitizens' => $seniorCitizens,
            'filterService' => $filterService,
            'filters' => $filterService->getAllFilters(),
            'activeFilters' => $filterService->getActiveFilters(),
            'activeFilterCount' => $filterService->getActiveFilterCount(),
            'barangays' => \App\Constants\Barangay::list(),
        ]);
    }

    /**
     * Display archived senior citizens.
     */
    public function archive(Request $request)
    {
        $query = SeniorCitizen::onlyTrashed();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('middlename', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%")
                  ->orWhere('osca_id', 'like', "%{$search}%");
            });
        }

        // Age filter: exact takes precedence over range
        if ($request->filled('age_exact') && is_numeric($request->age_exact)) {
            $query->where('age', (int)$request->age_exact);
        } elseif ($request->filled('age_range') && $request->age_range !== '') {
            $ageRange = $request->age_range;
            if (preg_match('/^(\d+)-(\d+)$/', $ageRange, $matches)) {
                $query->whereBetween('age', [(int)$matches[1], (int)$matches[2]]);
            } elseif ($ageRange === '80+') {
                $query->where('age', '>=', 80);
            }
        }

        $sort = $request->query('sort', 'name_asc');
        $query = $this->applySort($query, $sort);
        $archivedCitizens = $query->paginate(10)->appends($request->query());
        return view('senior-citizens.archive', compact('archivedCitizens'));
    }

    /**
     * Restore an archived senior citizen.
     */
    public function restore($id)
    {
        $seniorCitizen = SeniorCitizen::withTrashed()->findOrFail($id);
        $seniorCitizen->restore();

        return redirect()->route('senior-citizens.archive')
                        ->with('success', 'Senior citizen restored successfully!');
    }

    /**
     * Show the form for creating a new senior citizen.
     */
    public function create()
    {
        return view('senior-citizens.create');
    }

    /**
     * Store a newly created senior citizen in storage.
     */
    public function store(Request $request)
    {
        $barangayList = implode(',', \App\Constants\Barangay::list());
        $remarksList = implode(',', \App\Constants\Remarks::list());

        $validated = $request->validate([
            // Personal / Basic Information
            'osca_id' => 'required|string|unique:senior_citizens,osca_id',
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:50',
            'address' => 'required|string',
            'barangay' => 'required|in:' . $barangayList,
            'contact_number' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'nullable|string|max:255',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'nullable|in:Single,Married,Widowed,Divorced,Separated,Other',
            'citizenship' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:100',
            'educational_attainment' => 'nullable|in:No Formal Education,Elementary,High School,Vocational,College,Post-Graduate',

            // Health Condition Section
            'with_disability' => 'boolean',
            'type_of_disability' => 'nullable|string|max:255',
            'bedridden' => 'boolean',
            'with_assistive_device' => 'boolean',
            'type_of_assistive_device' => 'nullable|string|max:255',
            'with_critical_illness' => 'boolean',
            'specify_illness' => 'nullable|string',
            'philhealth_member' => 'boolean',
            'philhealth_id' => 'nullable|string|max:50',

            // Source of Income Section
            'is_pensioner' => 'boolean',
            'pension_type' => 'nullable|in:SSS,GSIS,PVAO,Private,Others',
            'monthly_pension_amount' => 'nullable|numeric|min:0',
            'other_income_source' => 'nullable|string',
            'total_monthly_income' => 'nullable|numeric|min:0',

            // Classification
            'is_indigent' => 'boolean',

            // Legacy fields for backward compatibility
            'sss' => 'boolean',
            'gsis' => 'boolean',
            'pvao' => 'boolean',
            'family_pension' => 'boolean',
            'brgy_official' => 'boolean',
            'waitlist' => 'boolean',
            'social_pension' => 'boolean',
            'remarks' => 'nullable|in:' . $remarksList,

            // Family Members
            'family_members' => 'nullable|array',
            'family_members.*.name' => 'nullable|string|max:255',
            'family_members.*.relationship' => 'nullable|string|max:100',
            'family_members.*.age' => 'nullable|integer|min:0',
            'family_members.*.civil_status' => 'nullable|string|max:50',
            'family_members.*.occupation' => 'nullable|string|max:255',
            'family_members.*.monthly_income' => 'nullable|numeric|min:0',
            'family_members.*.address' => 'nullable|string',
        ]);

        // Calculate age and age range
        $seniorCitizen = new SeniorCitizen($validated);
        $seniorCitizen->calculateAge();

        // Generate fullname from name parts
        $fullname = $validated['lastname'];
        if ($validated['firstname']) {
            $fullname .= ', ' . $validated['firstname'];
        }
        if ($validated['middlename']) {
            $fullname .= ' ' . $validated['middlename'];
        }
        if (!empty($validated['extension_name'])) {
            $fullname .= ' ' . $validated['extension_name'];
        }
        $seniorCitizen->fullname = trim($fullname);

        $seniorCitizen->save();

        // Store family members (handle JSON format)
        $familyMembers = [];
        
        if ($request->has('family_members_json') && !empty($request->family_members_json)) {
            $familyMembers = json_decode($request->family_members_json, true) ?? [];
        } elseif ($request->has('family_members')) {
            $familyMembers = $request->family_members ?? [];
        }
        
        foreach ($familyMembers as $member) {
            if (!empty($member['name'])) {
                FamilyMember::create([
                    'senior_citizen_id' => $seniorCitizen->id,
                    'name' => $member['name'],
                    'relationship' => $member['relationship'] ?? null,
                    'age' => $member['age'] ?? null,
                    'civil_status' => $member['civil_status'] ?? null,
                    'occupation' => $member['occupation'] ?? null,
                    'monthly_income' => $member['monthly_income'] ?? 0,
                    'address' => $member['address'] ?? null,
                ]);
            }
        }

        app(\App\Services\CacheService::class)->invalidateTag('dashboard');

        return redirect()->route('senior-citizens.index')
                        ->with('success', 'Senior citizen added successfully!');
    }

    /**
     * Display the specified senior citizen.
     */
    public function show(SeniorCitizen $seniorCitizen)
    {
        return view('senior-citizens.show', compact('seniorCitizen'));
    }

    /**
     * Show the form for editing the specified senior citizen.
     */
    public function edit(SeniorCitizen $seniorCitizen)
    {
        return view('senior-citizens.edit', compact('seniorCitizen'));
    }

    /**
     * Update the specified senior citizen in storage.
     */
    public function update(Request $request, SeniorCitizen $seniorCitizen)
    {
        $barangayList = implode(',', \App\Constants\Barangay::list());
        $remarksList = implode(',', \App\Constants\Remarks::list());

        $validated = $request->validate([
            // Personal / Basic Information
            'osca_id' => 'required|string|unique:senior_citizens,osca_id,' . $seniorCitizen->id,
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:50',
            'address' => 'required|string',
            'barangay' => 'required|in:' . $barangayList,
            'contact_number' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'nullable|string|max:255',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'nullable|in:Single,Married,Widowed,Divorced,Separated,Other',
            'citizenship' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:100',
            'educational_attainment' => 'nullable|in:No Formal Education,Elementary,High School,Vocational,College,Post-Graduate',

            // Health Condition Section
            'with_disability' => 'boolean',
            'type_of_disability' => 'nullable|string|max:255',
            'bedridden' => 'boolean',
            'with_assistive_device' => 'boolean',
            'type_of_assistive_device' => 'nullable|string|max:255',
            'with_critical_illness' => 'boolean',
            'specify_illness' => 'nullable|string',
            'philhealth_member' => 'boolean',
            'philhealth_id' => 'nullable|string|max:50',

            // Source of Income Section
            'is_pensioner' => 'boolean',
            'pension_type' => 'nullable|in:SSS,GSIS,PVAO,Private,Others',
            'monthly_pension_amount' => 'nullable|numeric|min:0',
            'other_income_source' => 'nullable|string',
            'total_monthly_income' => 'nullable|numeric|min:0',

            // Classification
            'is_indigent' => 'boolean',

            // Legacy fields for backward compatibility
            'sss' => 'boolean',
            'gsis' => 'boolean',
            'pvao' => 'boolean',
            'family_pension' => 'boolean',
            'brgy_official' => 'boolean',
            'waitlist' => 'boolean',
            'social_pension' => 'boolean',
            'remarks' => 'nullable|in:' . $remarksList,

            // Death Information (for deceased records)
            'date_of_death' => 'nullable|date|before_or_equal:today',
            'cause_of_death' => 'nullable|string|max:500',
            'death_certificate_number' => 'nullable|string|max:100',

            // Family Members
            'family_members' => 'nullable|array',
            'family_members.*.name' => 'nullable|string|max:255',
            'family_members.*.relationship' => 'nullable|string|max:100',
            'family_members.*.age' => 'nullable|integer|min:0',
            'family_members.*.civil_status' => 'nullable|string|max:50',
            'family_members.*.occupation' => 'nullable|string|max:255',
            'family_members.*.monthly_income' => 'nullable|numeric|min:0',
            'family_members.*.address' => 'nullable|string',
        ]);

        // Calculate age and age range
        $seniorCitizen->fill($validated);
        $seniorCitizen->calculateAge();

        // Generate fullname from name parts
        $fullname = $validated['lastname'];
        if ($validated['firstname']) {
            $fullname .= ', ' . $validated['firstname'];
        }
        if ($validated['middlename']) {
            $fullname .= ' ' . $validated['middlename'];
        }
        if ($validated['extension_name']) {
            $fullname .= ' ' . $validated['extension_name'];
        }
        $seniorCitizen->fullname = trim($fullname);

        $seniorCitizen->save();

        // Update family members
        if ($request->has('family_members')) {
            // Delete existing family members
            $seniorCitizen->familyMembers()->delete();

            // Add new family members
            foreach ($request->family_members as $member) {
                if (!empty($member['name'])) {
                    FamilyMember::create([
                        'senior_citizen_id' => $seniorCitizen->id,
                        'name' => $member['name'],
                        'relationship' => $member['relationship'] ?? null,
                        'age' => $member['age'] ?? null,
                        'civil_status' => $member['civil_status'] ?? null,
                        'occupation' => $member['occupation'] ?? null,
                        'monthly_income' => $member['monthly_income'] ?? 0,
                        'address' => $member['address'] ?? null,
                    ]);
                }
            }
        }

        app(\App\Services\CacheService::class)->invalidateTag('dashboard');

        return redirect()->route('senior-citizens.show', $seniorCitizen)
                        ->with('success', 'Senior citizen updated successfully!');
    }

    /**
     * Mark a senior citizen as deceased (with death details for SPISC).
     * This is specific to social pension recipients.
     */
    public function markDeceased(Request $request, SeniorCitizen $seniorCitizen)
    {
        // Only allow marking as deceased if they have social pension
        if (!$seniorCitizen->social_pension) {
            return redirect()->route('senior-citizens.index')
                            ->with('error', 'Only social pension recipients can be marked as deceased through this process.');
        }

        $validated = $request->validate([
            'date_of_death' => 'required|date|before_or_equal:today',
            'cause_of_death' => 'required|string|max:255',
            'death_certificate_registration_number' => 'required|string|max:100',
        ]);

        // Record the death information and set remarks to Deceased
        $seniorCitizen->update([
            'date_of_death' => $validated['date_of_death'],
            'cause_of_death' => $validated['cause_of_death'],
            'death_certificate_number' => $validated['death_certificate_registration_number'],
            'remarks' => 'Deceased',
        ]);

        app(\App\Services\CacheService::class)->invalidateTag('dashboard');

        return redirect()->route('senior-citizens.index')
                        ->with('success', "Deceased information recorded for {$seniorCitizen->firstname} {$seniorCitizen->lastname}. Updated in SPISC with quarterly pension restriction.");
    }

    /**
     * Archive the specified senior citizen (soft delete).
     * No longer requires death information - that's handled by markDeceased for SPISC.
     */
    public function destroy(Request $request, SeniorCitizen $seniorCitizen)
    {
        $seniorCitizen->delete();

        app(\App\Services\CacheService::class)->invalidateTag('dashboard');

        return redirect()->route('senior-citizens.index')
                        ->with('success', 'Senior citizen archived successfully! View archived records in the Archive section.');
    }

    /**
     * Display global change history for all senior citizens
     */
    public function history()
    {
        $auditLogs = AuditLog::where('auditable_type', SeniorCitizen::class)
            ->with('user', 'auditable')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('history', compact('auditLogs'));
    }

    /**
     * Display audit history for a senior citizen
     */
    public function auditHistory(SeniorCitizen $seniorCitizen)
    {
        $auditLogs = $seniorCitizen->auditLogs();
        return view('senior-citizens.audit-history', compact('seniorCitizen', 'auditLogs'));
    }

    /**
     * Apply sort to senior citizens query.
     */
    private function applySort($query, string $sort)
    {
        $dir = str_ends_with($sort, '_desc') ? 'desc' : 'asc';
        return match (explode('_', $sort)[0] ?? 'name') {
            'age' => $query->orderBy('age', $dir),
            'barangay' => $query->orderBy('barangay', $dir)->orderBy('lastname', 'asc')->orderBy('firstname', 'asc'),
            default => $query->orderBy('lastname', $dir)->orderBy('firstname', $dir),
        };
    }
}

