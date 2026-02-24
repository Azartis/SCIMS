<?php

namespace App\Http\Controllers;

use App\Models\SeniorCitizen;
use Illuminate\Http\Request;

class SeniorCitizenController extends Controller
{
    /**
     * Display a listing of the senior citizens.
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

        // Filter by pension type
        if ($request->filled('pension_type') && $request->pension_type !== '') {
            $pensionType = $request->pension_type;
            if ($pensionType === 'none') {
                // Get citizens with NO pensions
                $query->where(function ($q) {
                    $q->where('sss', false)
                      ->where('gsis', false)
                      ->where('pvao', false)
                      ->where('family_pension', false)
                      ->where('brgy_official', false);
                });
            } else {
                $query->where($pensionType, true);
            }
        }

        // Filter by status
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
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer|min:1',
            'sex' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'barangay' => 'required|in:' . $barangayList,
            'contact_number' => 'nullable|string|max:20',
            'osca_id' => 'required|string|unique:senior_citizens,osca_id',
            'sss' => 'boolean',
            'gsis' => 'boolean',
            'pvao' => 'boolean',
            'family_pension' => 'boolean',
            'brgy_official' => 'boolean',
            'waitlist' => 'boolean',
            'social_pension' => 'boolean',
            'remarks' => 'nullable|in:' . $remarksList,
        ]);

        // Generate fullname from name parts
        $validated['fullname'] = trim($validated['lastname'] . ', ' . $validated['firstname'] . 
                                    ($validated['middlename'] ? ' ' . $validated['middlename'] : ''));

        SeniorCitizen::create($validated);

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
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer|min:1',
            'sex' => 'required|in:Male,Female,Other',
            'address' => 'required|string',
            'barangay' => 'required|in:' . $barangayList,
            'contact_number' => 'nullable|string|max:20',
            'osca_id' => 'required|string|unique:senior_citizens,osca_id,' . $seniorCitizen->id,
            'sss' => 'boolean',
            'gsis' => 'boolean',
            'pvao' => 'boolean',
            'family_pension' => 'boolean',
            'brgy_official' => 'boolean',
            'waitlist' => 'boolean',
            'social_pension' => 'boolean',
            'remarks' => 'nullable|in:' . $remarksList,
        ]);

        // Generate fullname from name parts
        $validated['fullname'] = trim($validated['lastname'] . ', ' . $validated['firstname'] . 
                                    ($validated['middlename'] ? ' ' . $validated['middlename'] : ''));

        $seniorCitizen->update($validated);

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
