<?php

namespace App\Services;

use App\Models\SeniorCitizen;
use App\Models\PensionDistribution;
use App\Models\AuditLog;
use Illuminate\Support\Collection;

/**
 * Dashboard Service
 * 
 * Handles all dashboard data aggregation, calculations, and metrics.
 * All metrics are cached to ensure fast dashboard loads.
 * 
 * Responsibilities:
 * - Aggregate KPI metrics
 * - Generate chart data
 * - Compile activity feeds
 * - Calculate trends
 * - Format data for Blade views
 * 
 * @package App\Services
 */
class DashboardService extends BaseService
{
    public function __construct(
        private CacheService $cache
    ) {}

    /**
     * Get complete dashboard data
     * 
     * This is the main entry point for the dashboard controller.
     * Returns all data needed to render the dashboard view in the expected structure.
     * 
     * Data structure:
     * - metrics: KPI metrics with trends
     * - pensionStats: Pension status for current quarter
     * - distributions: Chart data (age, gender, pension trends)
     * - statistics: Aggregate statistics (average age, etc.)
     * - recentActivities: Activity feed
     * - currentQuarter: Current quarter string
     * - lastUpdated: Last update timestamp
     * 
     * @return array
     */
    public function getDashboardData(): array
    {
        try {
            $metrics = $this->getMetricsWithTrends();
            $pensionStats = $this->getPensionStats();
            $distributions = [
                'ageChart' => $this->getAgeDistribution(),
                'genderChart' => $this->getGenderDistribution(),
                'barangayChart' => $this->getBarangayDistribution(),
                'pensionTrendChart' => $this->getPensionTrends(),
            ];
            $statistics = $this->getStatistics();
            $activities = $this->getRecentActivities();
            $ageGroupStats = $this->getAgeGroupStats();

            return [
                'metrics' => $metrics,
                'pensionStats' => $pensionStats,
                'distributions' => $distributions,
                'statistics' => $statistics,
                'recentActivities' => $activities,
                'ageGroupStats' => $ageGroupStats,
                'currentQuarter' => $this->getCurrentQuarter(),
                'lastUpdated' => now()->format('M d, Y g:i A'),
            ];
        } catch (\Exception $e) {
            $this->log('Error retrieving dashboard data: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine(), 'error');
            return $this->getEmptyDashboard();
        }
    }

    /**
     * Get metrics with trend percentages
     * 
     * @return array
     */
    private function getMetricsWithTrends(): array
    {
        return $this->cache->rememberWithTag(
            'dashboard',
            'metrics_with_trends',
            CacheService::TTL['short'],
            function () {
                // Current period counts
                $totalSeniors = SeniorCitizen::count();
                $pensionRecipients = SeniorCitizen::where('social_pension', true)->count();
                $onWaitlist = SeniorCitizen::where('remarks', 'On Waitlist')->count();
                $withDisability = SeniorCitizen::where('with_disability', true)->count();

                // Previous period counts (30 days ago) for trend calculation
                $thirtyDaysAgo = now()->subDays(30);
                $totalSeniorsLastMonth = SeniorCitizen::where('created_at', '<', $thirtyDaysAgo)->count();
                
                // Calculate actual trends (percentage change)
                $totalTrend = $totalSeniorsLastMonth > 0 
                    ? round((($totalSeniors - $totalSeniorsLastMonth) / $totalSeniorsLastMonth) * 100, 1)
                    : 0;
                
                // Pension recipients trend (quarter-based)
                $currentQuarter = $this->getCurrentQuarter();
                $currentYear = now()->year;
                $lastQuarterClaimed = PensionDistribution::whereDate('disbursement_date', '>=', now()->subMonths(3))
                    ->where('status', 'claimed')
                    ->count();
                $thisQuarterClaimed = PensionDistribution::whereDate('disbursement_date', '>=', now()->startOfQuarter())
                    ->where('status', 'claimed')
                    ->count();
                    
                $pensionTrend = $lastQuarterClaimed > 0 
                    ? round((($thisQuarterClaimed - $lastQuarterClaimed) / $lastQuarterClaimed) * 100, 1)
                    : 0;

                // Waitlist trend (month-based)
                $waitlistLastMonth = SeniorCitizen::where('remarks', 'On Waitlist')
                    ->where('updated_at', '<', $thirtyDaysAgo)
                    ->count();
                $waitlistTrend = $waitlistLastMonth > 0 
                    ? round((($onWaitlist - $waitlistLastMonth) / $waitlistLastMonth) * 100, 1)
                    : 0;

                // Disability trend
                $disabilityLastMonth = SeniorCitizen::where('with_disability', true)
                    ->where('created_at', '<', $thirtyDaysAgo)
                    ->count();
                $disabilityTrend = $totalSeniorsLastMonth > 0 
                    ? round(($withDisability / $totalSeniors) * 100, 1) - round(($disabilityLastMonth / $totalSeniorsLastMonth) * 100, 1)
                    : 0;

                // Actual enrollment/participation rate (active pension recipients vs eligible)
                $eligibleForPension = SeniorCitizen::count(); // All seniors are eligible
                $enrollmentRate = $eligibleForPension > 0 
                    ? round(($pensionRecipients / $eligibleForPension) * 100, 1)
                    : 0;

                return [
                    'totalSeniors' => $totalSeniors,
                    'totalTrend' => $totalTrend, // Actual trend from previous month
                    'pensionRecipients' => $pensionRecipients,
                    'pensionTrend' => $pensionTrend, // Quarter-over-quarter trend
                    'onWaitlist' => $onWaitlist,
                    'waitlistTrend' => $waitlistTrend, // Month-over-month trend
                    'withDisability' => $withDisability,
                    'disabilityTrend' => $disabilityTrend, // Disability percentage trend
                    'pensionCoverage' => $eligibleForPension > 0 ? round(($pensionRecipients / $eligibleForPension) * 100, 1) : 0,
                    'enrollmentRate' => $enrollmentRate, // Actual enrollment rate
                    'disabilityRate' => $totalSeniors > 0 ? round(($withDisability / $totalSeniors) * 100, 1) : 0,
                ];
            }
        );
    }

    /**
     * Get summary statistics
     * 
     * @return array
     */
    private function getStatistics(): array
    {
        return $this->cache->rememberWithTag(
            'dashboard',
            'summary_statistics',
            CacheService::TTL['short'],
            function () {
                $averageAge = SeniorCitizen::avg('age');
                $oldest = SeniorCitizen::max('age');
                $youngest = SeniorCitizen::min('age');
                $totalSeniors = SeniorCitizen::count();
                $withDisability = SeniorCitizen::where('with_disability', true)->count();

                return [
                    'averageAge' => $averageAge ? number_format($averageAge, 1) : 'N/A',
                    'oldest' => $oldest ?? 'N/A',
                    'youngest' => $youngest ?? 'N/A',
                    'disabilityRate' => $totalSeniors > 0 ? number_format(($withDisability / $totalSeniors) * 100, 1) : 'N/A',
                ];
            }
        );
    }

    /**
     * Get pension status statistics (current quarter actual data)
     * 
     * @return array
     */
    public function getPensionStats(): array
    {
        return $this->cache->rememberWithTag(
            'dashboard',
            'pension_stats',
            CacheService::TTL['short'],
            function () {
                $currentQuarter = $this->getCurrentQuarter();
                $currentYear = now()->year;
                
                // Get actual quarter date range
                $quarterStartMonth = match($currentQuarter) {
                    'Q1' => 1,
                    'Q2' => 4,
                    'Q3' => 7,
                    'Q4' => 10,
                };
                
                $quarterStart = \Carbon\Carbon::createFromDate($currentYear, $quarterStartMonth, 1);
                $quarterEnd = (clone $quarterStart)->addMonths(3)->subDay();
                
                // Count actual distributed pensions for this quarter
                $claimed = PensionDistribution::whereBetween('disbursement_date', [$quarterStart, $quarterEnd])
                    ->where('status', 'claimed')
                    ->count();

                $unclaimed = PensionDistribution::whereBetween('disbursement_date', [$quarterStart, $quarterEnd])
                    ->where('status', 'unclaimed')
                    ->count();

                $total = $claimed + $unclaimed;
                $claimedPercent = $total > 0 ? round(($claimed / $total) * 100) : 0;

                return [
                    'claimed' => $claimed,
                    'unclaimed' => $unclaimed,
                    'claimedPercent' => $claimedPercent,
                ];
            }
        );
    }

    /**
     * Get age distribution for pie chart
     * 
     * @return array
     */
    public function getAgeDistribution(): array
    {
        return $this->cache->rememberWithTag(
            'dashboard',
            'age_distribution',
            CacheService::TTL['medium'],
            function () {
                $ranges = [
                    '60-64' => 0,
                    '65-69' => 0,
                    '70-74' => 0,
                    '75-79' => 0,
                    '80+' => 0,
                ];

                SeniorCitizen::selectRaw('age')
                    ->whereNull('deleted_at')
                    ->get()
                    ->each(function ($senior) use (&$ranges) {
                        if ($senior->age >= 80) $ranges['80+']++;
                        elseif ($senior->age >= 75) $ranges['75-79']++;
                        elseif ($senior->age >= 70) $ranges['70-74']++;
                        elseif ($senior->age >= 65) $ranges['65-69']++;
                        else $ranges['60-64']++;
                    });

                return [
                    'labels' => array_keys($ranges),
                    'data' => array_values($ranges),
                    'colors' => ['#0F172A', '#64748B', '#CBD5E1', '#E2E8F0', '#F1F5F9'],
                ];
            }
        );
    }

    /**
     * Get gender distribution for chart
     * 
     * @return array
     */
    public function getGenderDistribution(): array
    {
        return $this->cache->rememberWithTag(
            'dashboard',
            'gender_distribution',
            CacheService::TTL['medium'],
            function () {
                $data = SeniorCitizen::selectRaw('sex, count(*) as count')
                    ->whereNull('deleted_at')
                    ->groupBy('sex')
                    ->pluck('count', 'sex')
                    ->toArray();

                // Map sex codes to readable labels
                $mappedLabels = array_map(function($sex) {
                    return match(strtoupper($sex)) {
                        'M' => 'Male',
                        'F' => 'Female',
                        default => $sex
                    };
                }, array_keys($data));

                return [
                    'labels' => $mappedLabels,
                    'data' => array_values($data),
                    'colors' => ['#3B82F6', '#EC4899'],
                ];
            }
        );
    }

    /**
     * Get barangay distribution for charts
     * 
     * @return array
     */
    public function getBarangayDistribution(): array
    {
        return $this->cache->rememberWithTag(
            'dashboard',
            'barangay_distribution',
            CacheService::TTL['medium'],
            function () {
                $data = SeniorCitizen::selectRaw('barangay, count(*) as count')
                    ->whereNull('deleted_at')
                    ->groupBy('barangay')
                    ->orderByDesc('count')
                    ->limit(10)
                    ->pluck('count', 'barangay')
                    ->toArray();

                return [
                    'labels' => array_keys($data),
                    'data' => array_values($data),
                ];
            }
        );
    }

    /**
     * Get pension claims trends by quarter
     * Uses actual disbursement_date from distributions
     * 
     * @return array
     */
    public function getPensionTrends(): array
    {
        return $this->cache->rememberWithTag(
            'dashboard',
            'pension_trends',
            CacheService::TTL['medium'],
            function () {
                $currentYear = now()->year;
                $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
                $claimed = [];
                $unclaimed = [];

                foreach ($quarters as $q) {
                    // Calculate quarter date range
                    $quarterStartMonth = match($q) {
                        'Q1' => 1,
                        'Q2' => 4,
                        'Q3' => 7,
                        'Q4' => 10,
                    };
                    
                    $quarterStart = \Carbon\Carbon::createFromDate($currentYear, $quarterStartMonth, 1);
                    $quarterEnd = (clone $quarterStart)->addMonths(3)->subDay();
                    
                    $claimed[] = PensionDistribution::whereBetween('disbursement_date', [$quarterStart, $quarterEnd])
                        ->where('status', 'claimed')
                        ->count();

                    $unclaimed[] = PensionDistribution::whereBetween('disbursement_date', [$quarterStart, $quarterEnd])
                        ->where('status', 'unclaimed')
                        ->count();
                }

                return [
                    'labels' => $quarters,
                    'claimed' => $claimed,
                    'unclaimed' => $unclaimed,
                ];
            }
        );
    }

    /**
     * Get recent user activities
     * 
     * @param int $limit
     * @return array
     */
    public function getRecentActivities(int $limit = 5): array
    {
        return $this->cache->rememberWithTag(
            'dashboard',
            'recent_activities',
            CacheService::TTL['realtime'],
            fn() => AuditLog::with('user')
                ->latest()
                ->limit($limit)
                ->get()
                ->map(fn($log) => [
                    'id' => $log->id,
                    'action' => ucwords(str_replace('_', ' ', $log->activity)),
                    'user' => $log->user?->name ?? 'System',
                    'model' => $log->model_name,
                    'model_id' => $log->model_id,
                    'timestamp' => $log->created_at->format('M d, g:i A'),
                    'time_ago' => $log->created_at->diffForHumans(),
                ])
                ->toArray()
        );
    }

    /**
     * Get senior citizens by specific age (80, 85, 90, 95, 100)
     * 
     * @return array
     */
    public function getAgeGroupStats(): array
    {
        return $this->cache->rememberWithTag(
            'dashboard',
            'age_group_stats',
            CacheService::TTL['short'],
            function () {
                $ageGroups = [
                    'age_80' => [
                        'age' => 80,
                        'count' => 0,
                        'icon' => '👴',
                        'color' => 'slate'
                    ],
                    'age_85' => [
                        'age' => 85,
                        'count' => 0,
                        'icon' => '👴',
                        'color' => 'blue'
                    ],
                    'age_90' => [
                        'age' => 90,
                        'count' => 0,
                        'icon' => '👴',
                        'color' => 'purple'
                    ],
                    'age_95' => [
                        'age' => 95,
                        'count' => 0,
                        'icon' => '👴',
                        'color' => 'pink'
                    ],
                    'age_100' => [
                        'age' => 100,
                        'count' => 0,
                        'icon' => '🎂',
                        'color' => 'yellow'
                    ],
                ];

                // Get counts for each exact age
                foreach ($ageGroups as $key => &$group) {
                    $group['count'] = SeniorCitizen::where('age', $group['age'])
                        ->whereNull('deleted_at')
                        ->count();
                }

                return $ageGroups;
            }
        );
    }

    /**
     * Get current quarter (Q1, Q2, Q3, Q4)
     * 
     * @return string
     */
    public function getCurrentQuarter(): string
    {
        $month = now()->month;
        
        if ($month <= 3) return 'Q1';
        if ($month <= 6) return 'Q2';
        if ($month <= 9) return 'Q3';
        return 'Q4';
    }

    /**
     * Get an empty dashboard structure (fallback on error)
     * 
     * @return array
     */
    private function getEmptyDashboard(): array
    {
        return [
            'metrics' => [
                'totalSeniors' => 0,
                'totalTrend' => 0,
                'pensionRecipients' => 0,
                'pensionTrend' => 0,
                'onWaitlist' => 0,
                'waitlistTrend' => 0,
                'withDisability' => 0,
                'disabilityTrend' => 0,
                'pensionCoverage' => 0,
                'enrollmentRate' => 0,
                'disabilityRate' => 0,
            ],
            'pensionStats' => [
                'claimed' => 0,
                'unclaimed' => 0,
                'claimedPercent' => 0,
            ],
            'distributions' => [
                'ageChart' => ['labels' => [], 'data' => []],
                'genderChart' => ['labels' => [], 'data' => []],
                'barangayChart' => ['labels' => [], 'data' => []],
                'pensionTrendChart' => ['labels' => [], 'claimed' => [], 'unclaimed' => []],
            ],
            'statistics' => [
                'averageAge' => 'N/A',
                'oldest' => 'N/A',
                'youngest' => 'N/A',
                'disabilityRate' => 'N/A',
            ],
            'recentActivities' => [],
            'ageGroupStats' => [],
            'currentQuarter' => $this->getCurrentQuarter(),
            'lastUpdated' => now()->format('M d, Y g:i A'),
        ];
    }

    /**
     * Refresh all dashboard caches
     * Called when significant data changes
     * 
     * @return void
     */
    public function refreshDashboardCache(): void
    {
        $this->cache->invalidateTag('dashboard');
        $this->getDashboardData();
        $this->log('Dashboard cache refreshed');
    }
}
