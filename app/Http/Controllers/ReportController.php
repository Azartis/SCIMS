<?php

namespace App\Http\Controllers;

use App\Models\SeniorCitizen;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display reports page.
     */
    public function index()
    {
        $totalSeniorCitizens = SeniorCitizen::count();
        $waitlistCount = SeniorCitizen::where('waitlist', true)->count();
        $socialPensionCount = SeniorCitizen::where('social_pension', true)->count();
        $sssCount = SeniorCitizen::where('sss', true)->count();
        $gsisCount = SeniorCitizen::where('gsis', true)->count();
        $pvaoCount = SeniorCitizen::where('pvao', true)->count();

        $maleCount = SeniorCitizen::where('gender', 'Male')->count();
        $femaleCount = SeniorCitizen::where('gender', 'Female')->count();

        return view('reports.index', compact(
            'totalSeniorCitizens',
            'waitlistCount',
            'socialPensionCount',
            'sssCount',
            'gsisCount',
            'pvaoCount',
            'maleCount',
            'femaleCount'
        ));
    }

    /**
     * Generate CSV export.
     */
    public function export(Request $request)
    {
        $query = SeniorCitizen::query();

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('status')) {
            if ($request->status === 'waitlist') {
                $query->where('waitlist', true);
            } elseif ($request->status === 'social_pension') {
                $query->where('social_pension', true);
            }
        }

        $seniorCitizens = $query->get();

        $filename = 'senior_citizens_' . now()->format('Y-m-d_His') . '.csv';

        return response()->stream(
            function () use ($seniorCitizens) {
                $handle = fopen('php://output', 'w');

                // Add headers
                fputcsv($handle, [
                    'ID',
                    'Full Name',
                    'Date of Birth',
                    'Age',
                    'Gender',
                    'Address',
                    'Contact Number',
                    'OSCA ID',
                    'SSS',
                    'GSIS',
                    'PVAO',
                    'Family Pension',
                    'Brgy Official',
                    'Waitlist',
                    'Social Pension',
                    'Remarks'
                ]);

                // Add data
                foreach ($seniorCitizens as $citizen) {
                    fputcsv($handle, [
                        $citizen->id,
                        $citizen->getFormattedDisplayName(),
                        $citizen->date_of_birth,
                        $citizen->age,
                        $citizen->gender,
                        $citizen->address,
                        $citizen->contact_number,
                        $citizen->osca_id,
                        $citizen->sss ? 'Yes' : 'No',
                        $citizen->gsis ? 'Yes' : 'No',
                        $citizen->pvao ? 'Yes' : 'No',
                        $citizen->family_pension ? 'Yes' : 'No',
                        $citizen->brgy_official ? 'Yes' : 'No',
                        $citizen->waitlist ? 'Yes' : 'No',
                        $citizen->social_pension ? 'Yes' : 'No',
                        $citizen->remarks
                    ]);
                }

                fclose($handle);
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\""
            ]
        );
    }

    /**
     * Display detailed statistics.
     */
    public function statistics()
    {
        $pensionStats = [
            'sss' => SeniorCitizen::where('sss', true)->count(),
            'gsis' => SeniorCitizen::where('gsis', true)->count(),
            'pvao' => SeniorCitizen::where('pvao', true)->count(),
            'family_pension' => SeniorCitizen::where('family_pension', true)->count(),
            'brgy_official' => SeniorCitizen::where('brgy_official', true)->count(),
        ];

        $genderStats = [
            'male' => SeniorCitizen::where('gender', 'Male')->count(),
            'female' => SeniorCitizen::where('gender', 'Female')->count(),
            'other' => SeniorCitizen::where('gender', 'Other')->count(),
        ];

        $statusStats = [
            'waitlist' => SeniorCitizen::where('waitlist', true)->count(),
            'social_pension' => SeniorCitizen::where('social_pension', true)->count(),
        ];

        return view('reports.statistics', compact('pensionStats', 'genderStats', 'statusStats'));
    }
}
