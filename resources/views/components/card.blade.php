{{-- SaaS Card Component (Premium Design) --}}
@props([
    'title' => null,
    'subtitle' => null,
    'noPadding' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200']) }}>
    @if($title)
        <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 lg:py-5 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-slate-50 to-slate-50/50 dark:from-slate-800/50 dark:to-slate-800/30 flex items-center justify-between gap-2">
            <div class="min-w-0">
                <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-slate-900 dark:text-slate-50 truncate">{{ $title }}</h3>
                @if($subtitle)
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($headerAction))
                <div class="flex-shrink-0">{{ $headerAction }}</div>
            @endif
        </div>
    @endif

    <div @class([
        'px-3 sm:px-4 lg:px-6 py-3 sm:py-4 lg:py-5' => !$noPadding,
        'text-slate-900 dark:text-slate-50',
    ])>
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-3 sm:px-4 lg:px-6 py-2 sm:py-3 lg:py-4 bg-gradient-to-r from-slate-50 to-slate-50/50 dark:from-slate-800/50 dark:to-slate-800/30 border-t border-slate-200 dark:border-slate-700 text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-medium">
            {{ $footer }}
        </div>
    @endif
</div>
