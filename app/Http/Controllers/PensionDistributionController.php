<?php

namespace App\Http\Controllers;

use App\Models\PensionDistribution;
use App\Models\SeniorCitizen;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;

class PensionDistributionController extends Controller
{
    public function export(Request $request)
    {
        $filename = 'pension_distributions_' . now()->format('Ymd_His') . '.csv';
        $distributions = PensionDistribution::with('seniorCitizen')->orderBy('disbursement_date', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($distributions) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Disbursement Date','Lastname','Firstname','Middlename','Ext','Address','Amount','Status','Authorized Rep','Rep Relationship','Rep Contact','Claimed At']);

            foreach ($distributions as $d) {
                $sen = $d->seniorCitizen;
                fputcsv($out, [
                    $d->disbursement_date?->format('Y-m-d'),
                    $sen?->lastname,
                    $sen?->firstname,
                    $sen?->middlename,
                    $sen?->extension_name,
                    $sen?->address,
                    number_format($d->amount,2),
                    $d->status,
                    $d->authorized_rep_name,
                    $d->authorized_rep_relationship,
                    $d->authorized_rep_contact,
                    $d->claimed_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'disbursement_date' => 'required|date',
            'amount' => 'required|numeric',
        ]);

        // support both legacy single id and new multi-select
        $ids = [];
        if ($request->has('senior_citizen_ids')) {
            $ids = (array) $request->input('senior_citizen_ids', []);
        } elseif ($request->filled('senior_citizen_id')) {
            $ids = [(int) $request->input('senior_citizen_id')];
        }

        if (empty($ids)) {
            return redirect()->back()->withErrors(['senior_citizen_ids' => 'The senior citizen ids field is required.']);
        }

        // validate ids exist
        $exists = \App\Models\SeniorCitizen::whereIn('id', $ids)->count();
        if ($exists !== count($ids)) {
            return redirect()->back()->withErrors(['senior_citizen_ids' => 'One or more selected recipients are invalid.']);
        }

        // check eligibility based on death date and quarter
        $disDate = \Carbon\Carbon::parse($validated['disbursement_date']);
        $month = (int) $disDate->format('m');
        $q = (int) ceil($month / 3);
        $quarterStartMonth = ($q - 1) * 3 + 1;
        $quarterStart = \Carbon\Carbon::create($disDate->format('Y'), $quarterStartMonth, 1)->startOfDay();

        $ineligible = [];
        foreach ($ids as $sid) {
            $sen = \App\Models\SeniorCitizen::find($sid);
            if (! $sen) {
                $ineligible[] = $sid;
                continue;
            }
            if ($sen->date_of_death && $sen->date_of_death->lt($quarterStart)) {
                $ineligible[] = $sid;
            }
        }

        if (! empty($ineligible)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['senior_citizen_ids' => 'One or more selected seniors are not eligible to receive pension this quarter (they passed away earlier).']);
        }

        foreach ($ids as $sid) {
            PensionDistribution::create([
                'senior_citizen_id' => $sid,
                'disbursement_date' => $disDate->toDateString(),
                'amount' => $validated['amount'],
                'status' => 'unclaimed',
            ]);
        }

        app(\App\Services\CacheService::class)->invalidateTag('dashboard');

        return redirect()->route('spisc.index')->with('success', 'Distributions recorded.');
    }

    public function claim(Request $request, PensionDistribution $pension_distribution)
    {
        $data = $request->validate([
            'authorized_rep_name' => 'nullable|string|max:255',
            'authorized_rep_relationship' => 'nullable|string|max:255',
            'authorized_rep_contact' => 'nullable|string|max:255',
        ]);

        $pension_distribution->update(array_merge($data, [
            'status' => 'claimed',
            'claimed_at' => now(),
        ]));

        app(\App\Services\CacheService::class)->invalidateTag('dashboard');

        return redirect()->back()->with('success', 'Marked as claimed.');
    }

    public function show(PensionDistribution $pension_distribution)
    {
        $pension_distribution->load('seniorCitizen');
        return view('pension-distributions.show', compact('pension_distribution'));
    }
}
