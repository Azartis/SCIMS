@props([
    'activeFilters' => [],
    'resetUrl' => '#',
])

@if(count($activeFilters) > 0)
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700/50 rounded-lg p-4 mb-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-blue-900 dark:text-blue-100">{{ __('Active Filters:') }}</span>
            <div class="flex flex-wrap gap-2">
                @foreach($activeFilters as $name => $value)
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700/50">
                        <strong>{{ ucfirst(str_replace('_', ' ', $name)) }}:</strong> 
                        {{ is_array($value) ? implode(', ', $value) : $value }}
                    </span>
                @endforeach
            </div>
        </div>
        <a 
            href="{{ $resetUrl }}"
            class="inline-flex items-center gap-1 px-3 py-1 text-sm font-medium text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-lg transition"
        >
            ✕ {{ __('Clear Filters') }}
        </a>
    </div>
</div>
@endif
