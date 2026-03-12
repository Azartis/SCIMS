<?php

namespace App\Http\Controllers;

use App\Models\SeniorCitizen;
use App\Models\PensionDistribution;
use App\Services\FilterService;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;

class SpiscController extends Controller
{
    public function index(Request $request)
    {
        $query = SeniorCitizen::where('social_pension', true)
            ->with(['pensiondistributions' => function($q) {
                $q->orderBy('disbursement_date', 'desc')->take(1);
            }]);

        // Use FilterService for consistent filtering
        $filterService = new FilterService($query, $request);

        $filterService
            ->textSearch('search', ['lastname', 'firstname'], [
                'label' => 'Search by Name',
                'placeholder' => 'Last or first name...',
                'icon' => '🔍',
            ])
            ->select('barangay', 'barangay',
                array_combine(
                    \App\Constants\Barangay::list(),
                    \App\Constants\Barangay::list()
                ), [
                'label' => 'Barangay',
                'icon' => '📍',
            ])
            ->select('deceased', null, [
                'alive' => '✓ Alive only',
                'only' => '☠️ Deceased only',
            ], [
                'label' => 'Vital Status',
                'icon' => '💚',
            ])
            ->custom('deceased', function($q, $value) {
                if ($value === 'only') {
                    // Check BOTH date_of_death (new) AND remarks field (legacy support)
                    $q->where(function($subq) {
                        $subq->whereNotNull('date_of_death')
                             ->orWhere('remarks', 'Deceased');
                    });
                } elseif ($value === 'alive') {
                    // Only show if date_of_death is null AND remarks is not 'Deceased'
                    $q->where(function($subq) {
                        $subq->whereNull('date_of_death')
                             ->where('remarks', '!=', 'Deceased');
                    });
                }
            })
            ->select('status', null, [
                'claimed_personal' => '✓ Claimed (Personal)',
                'claimed_representative' => '👤 Claimed (Representative)',
                'unclaimed' => '⏳ Unclaimed',
            ], [
                'label' => 'Claim Status',
                'icon' => '📊',
            ])
            ->custom('status', function($q, $value) {
                if ($value === 'claimed_personal') {
                    $q->whereHas('pensiondistributions', function($sq) {
                        $sq->where('status', 'claimed')
                           ->whereNull('authorized_rep_name')
                           ->orderBy('disbursement_date', 'desc');
                    }, '>=', 1);
                } elseif ($value === 'claimed_representative') {
                    $q->whereHas('pensiondistributions', function($sq) {
                        $sq->where('status', 'claimed')
                           ->whereNotNull('authorized_rep_name')
                           ->orderBy('disbursement_date', 'desc');
                    }, '>=', 1);
                } elseif ($value === 'unclaimed') {
                    $q->whereHas('pensiondistributions', function($sq) {
                        $sq->where('status', 'unclaimed')
                           ->orderBy('disbursement_date', 'desc');
                    }, '>=', 1);
                }
            });

        $query = $filterService->getQuery();

        // Sort: name_asc, name_desc (backward compat: asc/desc)
        $sort = $request->query('sort', 'name_asc');
        $dir = in_array($sort, ['name_desc', 'asc', 'desc']) && ($sort === 'name_desc' || $sort === 'desc') ? 'desc' : 'asc';
        $seniors = $query->orderBy('lastname', $dir)->orderBy('firstname', $dir)
                         ->paginate(50)
                         ->appends($request->query());

        $barangays = \App\Constants\Barangay::list();
        // fetch full list of social pensioners for the modal selector
        $allSeniors = SeniorCitizen::where('social_pension', true)
            ->orderBy('lastname')
            ->get();

        return view('spisc', [
            'seniors' => $seniors,
            'barangays' => $barangays,
            'allSeniors' => $allSeniors,
            'filterService' => $filterService,
            'activeFilters' => $filterService->getActiveFilters(),
            'activeFilterCount' => $filterService->getActiveFilterCount(),
        ]);
    }

    public function updateStatus(Request $request, SeniorCitizen $seniorCitizen)
    {
        $data = $request->validate([
            'distribution_id' => 'required|exists:pension_distributions,id',
            'status' => 'required|in:claimed_personal,claimed_representative,unclaimed',
            'authorized_rep_name' => 'nullable|string|max:255',
            'authorized_rep_relationship' => 'nullable|string|max:255',
            'authorized_rep_contact' => 'nullable|string|max:255',
        ]);

        $distribution = PensionDistribution::findOrFail($data['distribution_id']);

        if ($data['status'] === 'claimed_personal') {
            $distribution->update([
                'status' => 'claimed',
                'claimed_at' => now(),
                'authorized_rep_name' => null,
                'authorized_rep_relationship' => null,
                'authorized_rep_contact' => null,
            ]);
        } elseif ($data['status'] === 'claimed_representative') {
            $distribution->update([
                'status' => 'claimed',
                'claimed_at' => now(),
                'authorized_rep_name' => $data['authorized_rep_name'] ?? null,
                'authorized_rep_relationship' => $data['authorized_rep_relationship'] ?? null,
                'authorized_rep_contact' => $data['authorized_rep_contact'] ?? null,
            ]);
        } else { // unclaimed
            $distribution->update([
                'status' => 'unclaimed',
                'claimed_at' => null,
                'authorized_rep_name' => null,
                'authorized_rep_relationship' => null,
                'authorized_rep_contact' => null,
            ]);
        }

        app(\App\Services\CacheService::class)->invalidateTag('dashboard');

        return redirect()->back()->with('success', 'Claim status updated successfully.');
    }
}
