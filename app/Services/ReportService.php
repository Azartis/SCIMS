<?php

namespace App\Services;

use App\Models\SeniorCitizen;
use Illuminate\Support\Collection;

/**
 * Report Service
 * 
 * Handles report generation, data aggregation, and exports.
 * All report calculations are cached appropriately.
 * 
 * Report Types:
 * - Health Distribution Report
 * - Barangay Distribution Report
 * - Classification Distribution Report
 * - Pension Status Report
 * - Custom filtered reports
 * 
 * @package App\Services
 */
class ReportService extends BaseService
{
    public function __construct(
        private CacheService $cache
    ) {}

    /**
     * Get health/age-based report
     * 
     * @return array
     */
    public function getHealthReport(): array
    {
        return $this->cache->rememberWithTag(
            'reports',
            'health_report',
            CacheService::TTL['medium'],
            function () {
                $seniors = SeniorCitizen::selectRaw('
                    CASE 
                        WHEN age >= 80 THEN "80+ years"
                        WHEN age >= 75 THEN "75-79 years"
                        WHEN age >= 70 THEN "70-74 years"
                        WHEN age >= 65 THEN "65-69 years"
                        ELSE "60-64 years"
                    END as age_group,
                    count(*) as count
                ')
                ->whereNull('deleted_at')
                ->groupBy('age_group')
                ->orderBy('age_group')
                ->get();

                $data = [];
                foreach (['60-64 years', '65-69 years', '70-74 years', '75-79 years', '80+ years'] as $group) {
                    $count = $seniors->firstWhere('age_group', $group)?->count ?? 0;
                    $data[$group] = $count;
                }

                return [
                    'title' => 'Age Distribution Report',
                    'data' => $data,
                    'total' => array_sum($data),
                    'generated_at' => now()->format('Y-m-d H:i:s'),
                ];
            }
        );
    }

    /**
     * Get barangay distribution report
     * 
     * @return array
     */
    public function getBarangayReport(): array
    {
        return $this->cache->rememberWithTag(
            'reports',
            'barangay_report',
            CacheService::TTL['medium'],
            function () {
                $data = SeniorCitizen::selectRaw('barangay, count(*) as count')
                    ->whereNull('deleted_at')
                    ->groupBy('barangay')
                    ->orderByDesc('count')
                    ->pluck('count', 'barangay')
                    ->toArray();

                return [
                    'title' => 'Barangay Distribution Report',
                    'data' => $data,
                    'total' => array_sum($data),
                    'generated_at' => now()->format('Y-m-d H:i:s'),
                ];
            }
        );
    }

    /**
     * Get classification distribution report
     * 
     * @return array
     */
    public function getClassificationReport(): array
    {
        return $this->cache->rememberWithTag(
            'reports',
            'classification_report',
            CacheService::TTL['medium'],
            function () {
                $data = SeniorCitizen::selectRaw('classification, count(*) as count')
                    ->whereNull('deleted_at')
                    ->where('classification', '!=', NULL)
                    ->groupBy('classification')
                    ->orderByDesc('count')
                    ->pluck('count', 'classification')
                    ->toArray();

                return [
                    'title' => 'Classification Distribution Report',
                    'data' => $data,
                    'total' => array_sum($data),
                    'generated_at' => now()->format('Y-m-d H:i:s'),
                ];
            }
        );
    }

    /**
     * Get pension status report
     * 
     * @return array
     */
    public function getPensionReport(): array
    {
        return $this->cache->rememberWithTag(
            'reports',
            'pension_report',
            CacheService::TTL['short'],
            function () {
                $total = SeniorCitizen::count();
                $withPension = SeniorCitizen::whereHas('pensionDistributions', function ($q) {
                    $q->where('status', 'claimed');
                })->count();

                return [
                    'title' => 'Social Pension Status Report',
                    'total_seniors' => $total,
                    'with_pension' => $withPension,
                    'without_pension' => $total - $withPension,
                    'percentage_covered' => $total > 0 ? round(($withPension / $total) * 100, 2) : 0,
                    'generated_at' => now()->format('Y-m-d H:i:s'),
                ];
            }
        );
    }

    /**
     * Get deceased seniors report
     * 
     * @return array
     */
    public function getDeceasedReport(): array
    {
        return $this->cache->rememberWithTag(
            'reports',
            'deceased_report',
            CacheService::TTL['medium'],
            function () {
                $data = SeniorCitizen::whereNotNull('date_of_death')
                    ->selectRaw('
                        YEAR(date_of_death) as year,
                        MONTH(date_of_death) as month,
                        count(*) as count
                    ')
                    ->groupByRaw('YEAR(date_of_death), MONTH(date_of_death)')
                    ->orderByDesc('year')
                    ->orderByDesc('month')
                    ->get()
                    ->map(fn($row) => [
                        'date' => \Carbon\Carbon::createFromDate($row->year, $row->month, 1)->format('M Y'),
                        'count' => $row->count,
                    ])
                    ->take(12);

                return [
                    'title' => 'Deceased Seniors Report (Last 12 Months)',
                    'data' => $data,
                    'total_deceased' => SeniorCitizen::whereNotNull('date_of_death')->count(),
                    'generated_at' => now()->format('Y-m-d H:i:s'),
                ];
            }
        );
    }

    /**
     * Get disability report
     * 
     * @return array
     */
    public function getDisabilityReport(): array
    {
        return $this->cache->rememberWithTag(
            'reports',
            'disability_report',
            CacheService::TTL['medium'],
            function () {
                $total = SeniorCitizen::count();
                $withDisability = SeniorCitizen::where('with_disability', true)->count();

                $byBarangay = SeniorCitizen::selectRaw('barangay, count(*) as count')
                    ->where('with_disability', true)
                    ->groupBy('barangay')
                    ->orderByDesc('count')
                    ->pluck('count', 'barangay')
                    ->toArray();

                return [
                    'title' => 'Disability Distribution Report',
                    'total_seniors' => $total,
                    'with_disability' => $withDisability,
                    'percentage' => $total > 0 ? round(($withDisability / $total) * 100, 2) : 0,
                    'by_barangay' => $byBarangay,
                    'generated_at' => now()->format('Y-m-d H:i:s'),
                ];
            }
        );
    }

    /**
     * Get gender distribution report
     * 
     * @return array
     */
    public function getGenderReport(): array
    {
        return $this->cache->rememberWithTag(
            'reports',
            'gender_report',
            CacheService::TTL['medium'],
            function () {
                $data = SeniorCitizen::selectRaw('gender, count(*) as count')
                    ->whereNull('deleted_at')
                    ->groupBy('gender')
                    ->pluck('count', 'gender')
                    ->toArray();

                return [
                    'title' => 'Gender Distribution Report',
                    'data' => $data,
                    'total' => array_sum($data),
                    'generated_at' => now()->format('Y-m-d H:i:s'),
                ];
            }
        );
    }

    /**
     * Get comprehensive summary report
     * 
     * @return array
     */
    public function getSummaryReport(): array
    {
        return [
            'executive_summary' => $this->cache->getDashboardMetrics(),
            'health_breakdown' => $this->getHealthReport(),
            'barangay_breakdown' => $this->getBarangayReport(),
            'classification_breakdown' => $this->getClassificationReport(),
            'pension_status' => $this->getPensionReport(),
            'disability_status' => $this->getDisabilityReport(),
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Export report data to CSV format array
     * 
     * @param string $reportType
     * @param array $filters
     * @return Collection
     */
    public function exportToCSV(string $reportType = 'summary', array $filters = []): Collection
    {
        try {
            return match($reportType) {
                'health' => $this->exportHealthToCSV(),
                'barangay' => $this->exportBarangayToCSV(),
                'classification' => $this->exportClassificationToCSV(),
                'pension' => $this->exportPensionToCSV(),
                'deceased' => $this->exportDeceasedToCSV(),
                'disability' => $this->exportDisabilityToCSV(),
                default => collect(),
            };
        } catch (\Exception $e) {
            $this->log("Error exporting {$reportType} report: " . $e->getMessage(), 'error');
            throw $e;
        }
    }

    private function exportHealthToCSV(): Collection
    {
        $report = $this->getHealthReport();
        return collect($report['data'])->map(fn($count, $group) => [
            'Age Group' => $group,
            'Count' => $count,
            'Percentage' => round(($count / $report['total']) * 100, 2) . '%',
        ]);
    }

    private function exportBarangayToCSV(): Collection
    {
        $report = $this->getBarangayReport();
        return collect($report['data'])->map(fn($count, $barangay) => [
            'Barangay' => $barangay,
            'Count' => $count,
            'Percentage' => round(($count / $report['total']) * 100, 2) . '%',
        ]);
    }

    private function exportClassificationToCSV(): Collection
    {
        $report = $this->getClassificationReport();
        return collect($report['data'])->map(fn($count, $classification) => [
            'Classification' => $classification,
            'Count' => $count,
            'Percentage' => round(($count / $report['total']) * 100, 2) . '%',
        ]);
    }

    private function exportPensionToCSV(): Collection
    {
        $report = $this->getPensionReport();
        return collect([
            ['Category' => 'Total Seniors', 'Count' => $report['total_seniors']],
            ['Category' => 'With Pension', 'Count' => $report['with_pension']],
            ['Category' => 'Without Pension', 'Count' => $report['without_pension']],
            ['Category' => 'Coverage %', 'Count' => $report['percentage_covered'] . '%'],
        ]);
    }

    private function exportDeceasedToCSV(): Collection
    {
        $report = $this->getDeceasedReport();
        return collect($report['data'])->map(fn($item) => [
            'Period' => $item['date'],
            'Count' => $item['count'],
        ])->prepend(['category' => 'Month-Month', 'count' => 'Deaths']);
    }

    private function exportDisabilityToCSV(): Collection
    {
        $report = $this->getDisabilityReport();
        $rows = collect([
            ['Category' => 'Total Seniors', 'Count' => $report['total_seniors']],
            ['Category' => 'With Disability', 'Count' => $report['with_disability']],
            ['Category' => 'Percentage', 'Count' => $report['percentage'] . '%'],
            ['separator' => ''],
        ]);

        foreach ($report['by_barangay'] as $barangay => $count) {
            $rows->push(['Barangay' => $barangay, 'With Disability' => $count]);
        }

        return $rows;
    }

    /**
     * Refresh all report caches
     * 
     * @return void
     */
    public function refreshReportCache(): void
    {
        $this->cache->invalidateTag('reports');
        $this->log('Report cache refreshed');
    }
}
