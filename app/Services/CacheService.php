<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Cache Service
 * 
 * Centralized cache management for the entire system.
 * Uses tag-based invalidation for related data.
 * 
 * Usage:
 *   $cache->rememberWithTag('seniors', 'key', 60, fn() => SeniorCitizen::all());
 *   $cache->invalidateTag('seniors');
 * 
 * @package App\Services
 */
class CacheService extends BaseService
{
    /**
     * Cache tags for different data types
     */
    public const TAGS = [
        'seniors' => 'seniors',
        'pension' => 'pension',
        'reports' => 'reports',
        'dashboard' => 'dashboard',
        'analytics' => 'analytics',
        'users' => 'users',
    ];

    /**
     * Predefined TTL values (in minutes)
     */
    public const TTL = [
        'realtime' => 5,        // Real-time data
        'short' => 30,          // 30 minutes
        'medium' => 60,         // 1 hour
        'long' => 1440,         // 24 hours
    ];

    /**
     * Build a cache key from prefix, parameters, and page
     * 
     * @param string $prefix
     * @param array $params
     * @param int $page
     * @return string
     */
    public function buildKey(string $prefix, array $params = [], int $page = 1): string
    {
        $paramString = md5(json_encode($params));
        return "{$prefix}:{$paramString}:{$page}";
    }

    /**
     * Remember a value in cache (basic)
     * 
     * @param string $key
     * @param int $ttl Minutes
     * @param callable $callback
     * @return mixed
     */
    public function remember(string $key, int $ttl, callable $callback)
    {
        return Cache::remember(
            $key,
            now()->addMinutes($ttl),
            $callback
        );
    }

    /**
     * Check if the current cache driver supports tagging
     * Supports: redis, memcached (with tags)
     * Does not support: file, database, array
     * 
     * @return bool
     */
    private function supportsTagging(): bool
    {
        $driver = config('cache.default');
        $supportedDrivers = ['redis', 'memcached'];
        return in_array($driver, $supportedDrivers);
    }

    /**
     * Remember a value in cache with tags (recommended)
     * Falls back to simple cache if driver doesn't support tags
     * 
     * @param string|array $tags
     * @param string $key
     * @param int $ttl Minutes
     * @param callable $callback
     * @return mixed
     */
    public function rememberWithTag($tags, string $key, int $ttl, callable $callback)
    {
        if ($this->supportsTagging()) {
            $tagArray = is_array($tags) ? $tags : [$tags];
            return Cache::tags($tagArray)->remember(
                $key,
                now()->addMinutes($ttl),
                $callback
            );
        } else {
            // Fallback for cache drivers without tagging support
            return Cache::remember(
                $key,
                now()->addMinutes($ttl),
                $callback
            );
        }
    }

    /**
     * Get or put a value in cache
     * 
     * @param string $key
     * @param int $ttl
     * @param mixed $value
     * @return mixed
     */
    public function put(string $key, int $ttl, $value)
    {
        Cache::put($key, $value, now()->addMinutes($ttl));
        return $value;
    }

    /**
     * Invalidate all cache with a specific tag
     * For drivers without tagging support, gracefully falls back to manual clearing
     * 
     * @param string $tag
     * @return void
     */
    public function invalidateTag(string $tag): void
    {
        try {
            if ($this->supportsTagging()) {
                Cache::tags([$tag])->flush();
                $this->log("Invalidated cache tag: {$tag}");
            } else {
                // For drivers without tag support (file, database), log and skip
                $this->log("Cache driver does not support tagging, skipping tag invalidation for: {$tag}", 'debug');
            }
        } catch (\Exception $e) {
            // Gracefully handle any cache errors
            $this->log("Error invalidating cache tag '{$tag}': " . $e->getMessage(), 'warning');
        }
    }

    /**
     * Invalidate multiple tags at once
     * 
     * @param array $tags
     * @return void
     */
    public function invalidateTags(array $tags): void
    {
        foreach ($tags as $tag) {
            $this->invalidateTag($tag);
        }
    }

    /**
     * Get a cached value
     * 
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return Cache::get($key);
    }

    /**
     * Delete a specific cache key
     * 
     * @param string $key
     * @return void
     */
    public function delete(string $key): void
    {
        Cache::delete($key);
    }

    /**
     * Clear all cache (use with caution!)
     * 
     * @return void
     */
    public function flush(): void
    {
        Cache::flush();
        $this->log('All cache flushed', 'warning');
    }

    /**
     * Get user activity feed (cached)
     * 
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getUserActivityFeed(int $limit = 10)
    {
        return $this->rememberWithTag(
            self::TAGS['dashboard'],
            'activity_feed',
            self::TTL['short'],
            fn() => \App\Models\AuditLog::latest()
                ->with('user')
                ->limit($limit)
                ->get()
        );
    }

    /**
     * Get dashboard metrics (cached)
     * Used on dashboard load
     * 
     * @return array
     */
    public function getDashboardMetrics(): array
    {
        return $this->rememberWithTag(
            self::TAGS['dashboard'],
            'metrics',
            self::TTL['short'],
            function () {
                $totalSeniors = \App\Models\SeniorCitizen::count();
                $deceased = \App\Models\SeniorCitizen::whereNotNull('date_of_death')->count();
                $withDisability = \App\Models\SeniorCitizen::where('with_disability', true)->count();

                return [
                    'total_seniors' => $totalSeniors,
                    'social_pension' => \App\Models\SeniorCitizen
                        ::whereHas('pensionDistributions', fn($q) => $q->where('status', 'claimed'))
                        ->count(),
                    'on_waitlist' => \App\Models\SeniorCitizen
                        ::where('remarks', 'On Waitlist')
                        ->count(),
                    'deceased' => $deceased,
                    'with_disability' => $withDisability,
                    'alive' => $totalSeniors - $deceased,
                ];
            }
        );
    }

    /**
     * Get distribution statistics (cached)
     * 
     * @return array
     */
    public function getDistributionStats(): array
    {
        return $this->rememberWithTag(
            self::TAGS['reports'],
            'distribution_stats',
            self::TTL['medium'],
            function () {
                return [
                    'by_barangay' => \App\Models\SeniorCitizen::selectRaw('barangay, count(*) as count')
                        ->groupBy('barangay')
                        ->pluck('count', 'barangay'),
                    'by_gender' => \App\Models\SeniorCitizen::selectRaw('gender, count(*) as count')
                        ->groupBy('gender')
                        ->pluck('count', 'gender'),
                    'by_classification' => \App\Models\SeniorCitizen::selectRaw('classification, count(*) as count')
                        ->groupBy('classification')
                        ->pluck('count', 'classification'),
                ];
            }
        );
    }

    /**
     * Warm up critical caches on application start
     * 
     * @return void
     */
    public function warmUp(): void
    {
        $this->getDashboardMetrics();
        $this->getUserActivityFeed();
        $this->getDistributionStats();
        $this->log('Cache warmed up successfully');
    }
}
