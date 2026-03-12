<?php

namespace App\Http\Controllers;

use App\Models\SeniorCitizen;
use Illuminate\Http\Request;
use App\Constants\Barangay;
use Illuminate\Support\Str;

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

    $maleCount = SeniorCitizen::where('sex', 'Male')->count();
    $femaleCount = SeniorCitizen::where('sex', 'Female')->count();

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

    if ($request->filled('sex')) {
        $query->where('sex', $request->sex);
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

                // Add headers following requested layout
                fputcsv($handle, [
                    'LAST NAME',
                    'FIRST NAME',
                    'MIDDLE NAME',
                    'ADDRESS',
                    'DATE OF BIRTH (MM-DD-YYYY)',
                    'AGE',
                    'SEX',
                    'DATE OF ISSUANCE (MM-DD)',
                    'OSCA ID',
                    'SSS',
                    'GSIS',
                    'PVAO',
                    'FAMILY PENSION',
                    'BRGY OFFICIAL',
                    'WAITLIST',
                    'SC SOCIAL',
                    'REMARKS'
                ]);

                // Add data
                foreach ($seniorCitizens as $citizen) {
                    fputcsv($handle, [
                            // name fields separated
                        $citizen->lastname,
                        $citizen->firstname,
                        $citizen->middlename,
                        $citizen->address,
                        // format DOB as mm-dd-yyyy for spreadsheet age formulas
                        $citizen->date_of_birth ? "'" . $citizen->date_of_birth->format('m-d-Y') : '',
                        $citizen->age,
                        $citizen->sex,
                        $citizen->issuance_date,
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

    $sexStats = [
        'male' => SeniorCitizen::where('sex', 'Male')->count(),
        'female' => SeniorCitizen::where('sex', 'Female')->count(),
        'other' => SeniorCitizen::where('sex', 'Other')->count(),
    ];

    $statusStats = [
            'waitlist' => SeniorCitizen::where('waitlist', true)->count(),
            'social_pension' => SeniorCitizen::where('social_pension', true)->count(),
        ];

    return view('reports.statistics', compact('pensionStats', 'sexStats', 'statusStats'));
    }

    /**
     * Health condition overview and optional filtered list
     */
    public function health(Request $request)
    {
        $conditions = [
            'with_disability' => 'With Disability',
            'bedridden' => 'Bedridden',
            'with_assistive_device' => 'With Assistive Device',
            'with_critical_illness' => 'With Critical Illness',
            'philhealth_member' => 'PhilHealth Member',
        ];

        $counts = [];
        foreach ($conditions as $key => $label) {
            $counts[$key] = SeniorCitizen::where($key, true)->count();
        }

        $condition = $request->query('condition');
        $seniorCitizens = null;
        if ($condition && array_key_exists($condition, $conditions)) {
            $query = SeniorCitizen::where($condition, true);

            // Filter by barangay
            if ($request->filled('barangay')) {
                $query->where('barangay', $request->barangay);
            }

            // Filter by sex
            if ($request->filled('sex')) {
                $query->where('sex', $request->sex);
            }

            // Additional filters specific to condition
            if ($condition === 'with_disability' && $request->filled('type_of_disability')) {
                $query->where('type_of_disability', 'like', "%{$request->type_of_disability}%");
            }
            if ($condition === 'with_assistive_device' && $request->filled('type_of_assistive_device')) {
                $query->where('type_of_assistive_device', 'like', "%{$request->type_of_assistive_device}%");
            }
            if ($condition === 'with_critical_illness' && $request->filled('specify_illness')) {
                $query->where('specify_illness', 'like', "%{$request->specify_illness}%");
            }
            if ($condition === 'philhealth_member' && $request->filled('philhealth_id')) {
                $query->where('philhealth_id', 'like', "%{$request->philhealth_id}%");
            }

            $this->applyAgeFilter($query, $request);

            // Filter by search (name or OSCA ID)
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

            // Sort by name
            $sort = $request->query('sort', 'name_asc');
            $dir = in_array($sort, ['name_desc', 'desc']) ? 'desc' : 'asc';
            $query->orderBy('lastname', $dir)->orderBy('firstname', $dir);

            $seniorCitizens = $query->paginate(10)->appends($request->query());
        }

        return view('reports.health', compact('conditions', 'counts', 'condition', 'seniorCitizens'));
    }

    /**
     * Barangay overview and filtered list
     */
    public function barangay(Request $request)
    {
        $barangays = Barangay::list();
        $counts = [];
        foreach ($barangays as $b) {
            $counts[$b] = SeniorCitizen::where('barangay', $b)->count();
        }

        $selected = $request->query('barangay');
        $seniorCitizens = null;
        if ($selected && in_array($selected, $barangays)) {
            $query = SeniorCitizen::where('barangay', $selected);

            // Filter by sex
            if ($request->filled('sex')) {
                $query->where('sex', $request->sex);
            }

            $this->applyAgeFilter($query, $request);

            // Filter by search (name or OSCA ID)
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

            $sort = $request->query('sort', 'name_asc');
            $dir = in_array($sort, ['name_desc', 'desc']) ? 'desc' : 'asc';
            $query->orderBy('lastname', $dir)->orderBy('firstname', $dir);

            $seniorCitizens = $query->paginate(10)->appends($request->query());
        }

        return view('reports.barangay', compact('barangays', 'counts', 'selected', 'seniorCitizens'));
    }

    /**
     * Export barangay filtered results to CSV
     */
    public function exportBarangay(Request $request)
    {
        $query = SeniorCitizen::query();

        if ($request->filled('barangay')) {
            $query->where('barangay', $request->barangay);
        }

        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }

        $this->applyAgeFilter($query, $request);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('middlename', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%")
                  ->orWhere('osca_id', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        $sort = $request->query('sort', 'name_asc');
        $dir = in_array($sort, ['name_desc', 'desc']) ? 'desc' : 'asc';
        $query->orderBy('lastname', $dir)->orderBy('firstname', $dir);

        $seniorCitizens = $query->get();

        $filename = 'barangay_' . ($request->filled('barangay') ? Str::slug($request->barangay) . '_' : '') . now()->format('Y-m-d_His') . '.csv';

        return response()->stream(function () use ($seniorCitizens) {
            $handle = fopen('php://output', 'w');

            // match same format as main export but include Barangay column
            fputcsv($handle, [
                'LAST NAME',
                'FIRST NAME',
                'MIDDLE NAME',
                'BARANGAY',
                'ADDRESS',
                'DATE OF BIRTH (MM-DD-YYYY)',
                'AGE',
                'SEX',
                'DATE OF ISSUANCE (MM-DD)',
                'OSCA ID',
                'SSS',
                'GSIS',
                'PVAO',
                'FAMILY PENSION',
                'BRGY OFFICIAL',
                'WAITLIST',
                'SC SOCIAL',
                'REMARKS',
            ]);

            foreach ($seniorCitizens as $citizen) {
                fputcsv($handle, [
                    $citizen->lastname,
                    $citizen->firstname,
                    $citizen->middlename,
                    $citizen->barangay,
                    $citizen->address,
                    $citizen->date_of_birth ? "'" . $citizen->date_of_birth->format('m-d-Y') : '',
                    $citizen->age,
                    $citizen->sex,
                    $citizen->issuance_date,
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
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    /**
     * Export health condition filtered results to CSV
     */
    public function exportHealth(Request $request)
    {
        $condition = $request->query('condition');
        $conditions = [
            'with_disability' => 'With Disability',
            'bedridden' => 'Bedridden',
            'with_assistive_device' => 'With Assistive Device',
            'with_critical_illness' => 'With Critical Illness',
            'philhealth_member' => 'PhilHealth Member',
        ];

        if (!$condition || !array_key_exists($condition, $conditions)) {
            abort(400, 'Invalid health condition');
        }

        $query = SeniorCitizen::where($condition, true);

        if ($request->filled('barangay')) {
            $query->where('barangay', $request->barangay);
        }

        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }

        // condition-specific filters for export
        if ($condition === 'with_disability' && $request->filled('type_of_disability')) {
            $query->where('type_of_disability', 'like', "%{$request->type_of_disability}%");
        }
        if ($condition === 'with_assistive_device' && $request->filled('type_of_assistive_device')) {
            $query->where('type_of_assistive_device', 'like', "%{$request->type_of_assistive_device}%");
        }
        if ($condition === 'with_critical_illness' && $request->filled('specify_illness')) {
            $query->where('specify_illness', 'like', "%{$request->specify_illness}%");
        }
        if ($condition === 'philhealth_member' && $request->filled('philhealth_id')) {
            $query->where('philhealth_id', 'like', "%{$request->philhealth_id}%");
        }

        $this->applyAgeFilter($query, $request);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('middlename', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%")
                  ->orWhere('osca_id', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        $sort = $request->query('sort', 'name_asc');
        $dir = in_array($sort, ['name_desc', 'desc']) ? 'desc' : 'asc';
        $query->orderBy('lastname', $dir)->orderBy('firstname', $dir);

        $seniorCitizens = $query->get();

        $filename = 'health_' . Str::slug($condition) . '_' . now()->format('Y-m-d_His') . '.csv';

        // closure needs the condition variable as well
        return response()->stream(function () use ($seniorCitizens, $condition) {
            $handle = fopen('php://output', 'w');

            // build headers array and include any extra column for the specific condition
            $headers = [
                'LAST NAME',
                'FIRST NAME',
                'MIDDLE NAME',
                'BARANGAY',
                'ADDRESS',
                'DATE OF BIRTH (MM-DD-YYYY)',
                'AGE',
                'SEX',
                'DATE OF ISSUANCE (MM-DD)',
                'OSCA ID',
            ];

            if ($condition === 'with_disability') {
                $headers[] = 'Disability Type';
            } elseif ($condition === 'with_assistive_device') {
                $headers[] = 'Device';
            } elseif ($condition === 'with_critical_illness') {
                $headers[] = 'Illness';
            } elseif ($condition === 'philhealth_member') {
                $headers[] = 'PHIC ID';
            }

            $headers = array_merge($headers, [
                'SSS',
                'GSIS',
                'PVAO',
                'Family Pension',
                'Waitlist',
                'SC SOCIAL',
                'Remarks',
            ]);

            fputcsv($handle, $headers);

            foreach ($seniorCitizens as $citizen) {
                // base row data
                $row = [
                    $citizen->lastname,
                    $citizen->firstname,
                    $citizen->middlename,
                    $citizen->barangay,
                    $citizen->address,
                    $citizen->date_of_birth ? "'" . $citizen->date_of_birth->format('m-d-Y') : '',
                    $citizen->age,
                    $citizen->sex,
                    $citizen->issuance_date,
                    $citizen->osca_id,
                ];

                // include the type column if the condition has one
                if ($condition === 'with_disability') {
                    $row[] = $citizen->type_of_disability;
                } elseif ($condition === 'with_assistive_device') {
                    $row[] = $citizen->type_of_assistive_device;
                } elseif ($condition === 'with_critical_illness') {
                    $row[] = $citizen->specify_illness;
                } elseif ($condition === 'philhealth_member') {
                    $row[] = $citizen->philhealth_id;
                }

                // suffix columns
                $row = array_merge($row, [
                    $citizen->sss ? 'Yes' : 'No',
                    $citizen->gsis ? 'Yes' : 'No',
                    $citizen->pvao ? 'Yes' : 'No',
                    $citizen->family_pension ? 'Yes' : 'No',
                    $citizen->waitlist ? 'Yes' : 'No',
                    $citizen->social_pension ? 'Yes' : 'No',
                    $citizen->remarks,
                ]);

                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    /**
     * Deceased / archived records with filtering and sorting
     */
    public function deceased(Request $request)
    {
        $query = SeniorCitizen::onlyTrashed();

        // Filter by barangay
        if ($request->filled('barangay') && $request->barangay !== '') {
            $query->where('barangay', $request->barangay);
        }

        // Filter by sex
        if ($request->filled('sex') && $request->sex !== '') {
            $query->where('sex', $request->sex);
        }

        $this->applyAgeFilter($query, $request);

        $sort = $request->query('sort', 'name_asc');
        $dir = in_array($sort, ['name_desc', 'desc']) ? 'desc' : 'asc';
        $query->orderBy('lastname', $dir)->orderBy('firstname', $dir);

        $deceasedCount = SeniorCitizen::onlyTrashed()->count();
        $seniorCitizens = $query->paginate(10)->appends($request->query());
        $barangays = Barangay::list();

        return view('reports.deceased', compact('deceasedCount', 'seniorCitizens', 'barangays'));
    }

    /**
     * Show a trashed senior citizen in the same display format
     */
    public function deceasedShow($id)
    {
        $seniorCitizen = SeniorCitizen::withTrashed()->findOrFail($id);
        return view('senior-citizens.show', compact('seniorCitizen'));
    }

    private function applyAgeFilter($query, Request $request): void
    {
        if ($request->filled('age_exact') && is_numeric($request->age_exact)) {
            $query->where('age', (int)$request->age_exact);
        } elseif ($request->filled('age_range') && $request->age_range !== '') {
            $val = $request->age_range;
            if (preg_match('/^(\d+)-(\d+)$/', $val, $m)) {
                $query->whereRaw('age >= ? AND age <= ?', [(int)$m[1], (int)$m[2]]);
            } elseif ($val === '80+') {
                $query->where('age', '>=', 80);
            }
        }
    }
}
