{{--
  Unified filter bar - collapsible, consistent & compact across pages.
  Usage:
  <x-filter-bar :action="$action" :resetUrl="$resetUrl" :hasActiveFilters="$hasActiveFilters">
    ... filter fields (inputs, selects) ...
  </x-filter-bar>
--}}
@props([
    'action' => request()->url(),
    'resetUrl' => request()->url(),
    'hasActiveFilters' => false,
    'activeCount' => 0,
])

<div x-data="{ open: {{ $hasActiveFilters ? 'true' : 'false' }} }" class="mb-4">
    <!-- Filter Toggle Header -->
    <div class="flex items-center justify-between mb-2">
        <button
            type="button"
            @click="open = !open"
            class="flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 transition-colors"
        >
            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-xs md:text-sm">🔍 Filters</span>
            @if($activeCount > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300">
                    {{ $activeCount }} active
                </span>
            @endif
        </button>
        <a href="{{ $resetUrl }}" class="text-xs md:text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">
            ↻ Reset
        </a>
    </div>

    <!-- Filter Panel -->
    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-3 md:p-4 shadow-sm">
        <form method="GET" action="{{ $action }}" class="space-y-3">
            <!-- Filter Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3">
                {{ $slot }}
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 dark:bg-blue-600 text-white text-xs md:text-sm font-semibold rounded-md hover:bg-blue-700 dark:hover:bg-blue-500 transition">
                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <span class="hidden sm:inline">Apply</span>
                </button>
                <a href="{{ $resetUrl }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs md:text-sm font-medium rounded-md hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                    <span class="hidden sm:inline">Clear all</span>
                    <span class="sm:hidden">Clear</span>
                </a>
            </div>
        </form>
    </div>
</div>
