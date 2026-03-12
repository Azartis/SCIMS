{{-- 
    SaaS KPI Card Component
    
    Displays a single KPI metric with title, value, and optional trend
    
    Props:
    - title (string) - Metric title
    - value (mixed) - The main metric value
    - trend (int|null) - Percentage change (positive or negative)
    - trendLabel (string) - Text to show with trend
    - icon (slot) - Icon slot
    - color: slate|blue|green|red|amber (default: blue)
    
    Usage:
    <x-kpi-card title="Total Seniors" :value="1250" :trend="12" trendLabel="from last month" />
--}}

@props([
    'title' => '',
    'value' => 0,
    'trend' => null,
    'trendLabel' => 'from last period',
    'color' => 'blue',
])

@php
    $trendColor = $trend && $trend < 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400';
@endphp

<div class="rounded-2xl p-4 sm:p-5 bg-white dark:bg-slate-800 w-full shadow-sm hover:shadow-md transition-shadow duration-200 border border-slate-100 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-2 sm:gap-3">
        <div class="min-w-0">
            <p class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">{{ $title }}</p>
            <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 dark:text-slate-50 mt-2 sm:mt-3 truncate">{{ $value }}</p>
            
            @if($trend !== null)
                <p class="text-xs sm:text-sm {{ $trendColor }} mt-2 sm:mt-3 font-medium">
                    @if($trend >= 0)
                        <span class="inline-block mr-1">↑</span>
                    @else
                        <span class="inline-block mr-1">↓</span>
                    @endif
                    {{ abs($trend) }}% {{ $trendLabel }}
                </p>
            @endif
        </div>
        
        @if(isset($icon))
            <div class="text-3xl sm:text-4xl opacity-15 flex-shrink-0">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
