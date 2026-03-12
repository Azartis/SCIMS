@props([
    'filters' => [],
    'activeFilterCount' => 0,
    'resetUrl' => '#',
    'submitRoute' => '#',
])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                🔍 {{ __('Advanced Filters') }}
                @if($activeFilterCount > 0)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                        {{ $activeFilterCount }} {{ __('active') }}
                    </span>
                @endif
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Refine results using the filters below') }}</p>
        </div>
        <div class="flex gap-2">
            <button 
                type="button" 
                x-data 
                @click="document.querySelector('[data-filter-form]')?.reset(); window.location.href = '{{ $resetUrl }}'"
                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center gap-2"
            >
                🔄 {{ __('Clear All') }}
            </button>
            <details class="group">
                <summary class="cursor-pointer px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition list-none flex items-center gap-2">
                    <span>💡 {{ __('Help') }}</span>
                    <span class="group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-10 p-4 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                        <strong>{{ __('Filter Tips:') }}</strong>
                    </p>
                    <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-2">
                        <li>✓ {{ __('Use search to find by name, ID, or number') }}</li>
                        <li>✓ {{ __('Combine multiple filters for precise results') }}</li>
                        <li>✓ {{ __('Filters are applied instantly') }}</li>
                        <li>✓ {{ __('Click "Clear All" to reset') }}</li>
                        <li>✓ {{ __('Bookmark filtered results in your browser') }}</li>
                    </ul>
                </div>
            </details>
        </div>
    </div>

    <form method="GET" action="{{ $submitRoute }}" class="space-y-4" data-filter-form>
        <!-- Render filter groups -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{ $slot }}
        </div>

        <!-- Filter Actions -->
        <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button 
                type="submit" 
                class="inline-flex items-center px-6 py-2.5 bg-blue-600 dark:bg-blue-700 text-white rounded-lg font-semibold hover:bg-blue-700 dark:hover:bg-blue-600 transition"
            >
                🔍 {{ __('Apply Filters') }}
            </button>
            <button 
                type="button"
                x-data
                @click="document.querySelector('[data-filter-form]')?.reset()"
                class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition"
            >
                ↻ {{ __('Reset') }}
            </button>
        </div>
    </form>
</div>
