<?php

namespace App\Http\Controllers;

use App\Models\SeniorCitizen;
use App\Models\FamilyMember;
use Illuminate\Http\Request;

class SeniorCitizenController extends Controller
{
    /**
     * Display a listing of the senior citizens with advanced filtering.
     */
    public function index(Request $request)
    {
        $query = SeniorCitizen::withoutTrashed();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('middlename', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%")
                  ->orWhere('osca_id', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by sex
        if ($request->filled('sex') && $request->sex !== '') {
            $query->where('sex', $request->sex);
        }

        // Filter by barangay
        if ($request->filled('barangay') && $request->barangay !== '') {
            $query->where('barangay', $request->barangay);
        }

        // Filter by classification/categorization
        if ($request->filled('classification') && $request->classification !== '') {
            $classification = $request->classification;
            switch ($classification) {
                case 'pensioners':
                    $query->where('is_pensioner', true);
                    break;
                case 'indigent':
                    $query->where('is_indigent', true);
                    break;
                case 'with_disability':
                    $query->where('with_disability', true);
                    break;
                case 'bedridden':
                    $query->where('bedridden', true);
                    break;
                case 'critical_illness':
                    $query->where('with_critical_illness', true);
                    break;
            }
        }

        // Filter by age range
        if ($request->filled('age_range') && $request->age_range !== '') {
            $query->where('age_range', $request->age_range);
        }

        // Legacy filters for backward compatibility
        if ($request->filled('pension_type') && $request->pension_type !== '') {
            $pensionType = $request->pension_type;
            if ($pensionType === 'none') {
                $query->where('is_pensioner', false);
            } else {
                $query->where('pension_type', $pensionType);
            }
        }

        if ($request->filled('status') && $request->status !== '') {
            $status = $request->status;
            if ($status === 'waitlist') {
                $query->where('waitlist', true);
            } elseif ($status === 'social_pension') {
                $query->where('social_pension', true);
            }
        }

        $seniorCitizens = $query->paginate(10)->appends($request->query());
        return view('senior-citizens.index', compact('seniorCitizens'));
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
            'sex' => 'required|in:Male,Female,Other',
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
        if ($validated['extension_name']) {
            $fullname .= ' ' . $validated['extension_name'];
        }
        $seniorCitizen->fullname = trim($fullname);

        $seniorCitizen->save();

        // Store family members
        if ($request->has('family_members')) {
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
            'sex' => 'required|in:Male,Female,Other',
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

        return redirect()->route('senior-citizens.show', $seniorCitizen)
                        ->with('success', 'Senior citizen updated successfully!');
    }

    /**
     * Archive the specified senior citizen (soft delete).
     */
    public function destroy(SeniorCitizen $seniorCitizen)
    {
        $seniorCitizen->delete();

        return redirect()->route('senior-citizens.index')
                        ->with('success', 'Senior citizen archived successfully! View archived records in the Archive section.');
    }
}

