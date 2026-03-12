<?php

namespace App\Http\Controllers;

use App\Models\SeniorCitizen;
use App\Models\PensionDistribution;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgeMilestoneController extends Controller
{
    /**
     * Display seniors grouped by milestone ages (80, 85, 90, 95, 100)
     */
    public function index(Request $request)
    {
        $selectedAge = $request->query('age');
        $milestoneAges = $selectedAge ? [(int)$selectedAge] : [80, 85, 90, 95, 100];
        
        $ageGroups = [];

        foreach ($milestoneAges as $age) {
            $seniors = SeniorCitizen::where('age', $age)
                ->whereNull('deleted_at')
                ->with('pensionDistributions')
                ->orderBy('lastname', 'asc')
                ->orderBy('firstname', 'asc')
                ->get();

            $ageGroups[$age] = [
                'count' => $seniors->count(),
                'seniors' => $seniors,
                'icon' => $age === 100 ? '🎂' : '👴',
                'color' => match($age) {
                    80 => 'slate',
                    85 => 'blue',
                    90 => 'purple',
                    95 => 'pink',
                    100 => 'yellow',
                    default => 'slate'
                }
            ];
        }

        return view('age-milestones.index', compact('ageGroups', 'selectedAge'));
    }

    /**
     * Distribute benefits to seniors of a specific age
     */
    public function distribute(Request $request, $age)
    {
        $validated = $request->validate([
            'disbursement_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'senior_citizen_ids' => 'required|array|min:1',
            'senior_citizen_ids.*' => 'integer|exists:senior_citizens,id',
        ]);

        $disbursementDate = Carbon::parse($validated['disbursement_date']);
        $month = (int) $disbursementDate->format('m');
        $quarter = (int) ceil($month / 3);
        $quarterStartMonth = ($quarter - 1) * 3 + 1;
        $quarterStart = Carbon::create($disbursementDate->format('Y'), $quarterStartMonth, 1)->startOfDay();

        // Check eligibility - senior must not have passed away before quarter start
        $ineligible = [];
        foreach ($validated['senior_citizen_ids'] as $seniorId) {
            $senior = SeniorCitizen::find($seniorId);
            if (!$senior || ($senior->date_of_death && $senior->date_of_death->lt($quarterStart))) {
                $ineligible[] = $seniorId;
            }
        }

        if (!empty($ineligible)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['senior_citizen_ids' => 'One or more selected seniors are not eligible (deceased).']);
        }

        // Create distributions for each selected senior
        $count = 0;
        foreach ($validated['senior_citizen_ids'] as $seniorId) {
            // Check if distribution already exists for this senior and date
            $existing = PensionDistribution::where('senior_citizen_id', $seniorId)
                ->where('disbursement_date', $disbursementDate->toDateString())
                ->first();

            if (!$existing) {
                PensionDistribution::create([
                    'senior_citizen_id' => $seniorId,
                    'disbursement_date' => $disbursementDate->toDateString(),
                    'amount' => $validated['amount'],
                    'status' => 'unclaimed',
                ]);
                $count++;
            }
        }

        app(\App\Services\CacheService::class)->invalidateTag('dashboard');

        return redirect()->route('age-milestones.index')
            ->with('success', "Successfully distributed benefits to $count senior(s).");
    }

    /**
     * Mark a distribution as claimed
     */
    public function claimDistribution(Request $request, $distribution)
    {
        $pensionDistribution = PensionDistribution::findOrFail($distribution);

        $validated = $request->validate([
            'authorized_rep_name' => 'nullable|string|max:255',
            'authorized_rep_relationship' => 'nullable|string|max:255',
            'authorized_rep_contact' => 'nullable|string|max:255',
        ]);

        $pensionDistribution->update(array_merge($validated, [
            'status' => 'claimed',
            'claimed_at' => now(),
        ]));

        app(\App\Services\CacheService::class)->invalidateTag('dashboard');

        return redirect()->back()->with('success', 'Distribution marked as claimed.');
    }
}
