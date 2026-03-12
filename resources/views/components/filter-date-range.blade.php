@props([
    'nameFrom' => 'from_date',
    'nameTo' => 'to_date',
    'label' => 'Date Range',
    'icon' => '📅',
    'valueFrom' => '',
    'valueTo' => '',
])

<div class="lg:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
        <span>{{ $icon }}</span>
        {{ $label }}
    </label>
    <div class="flex gap-2">
        <input 
            type="date"
            id="{{ $nameFrom }}"
            name="{{ $nameFrom }}"
            value="{{ $valueFrom }}"
            class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 transition"
            placeholder="From"
        />
        <span class="flex items-center text-gray-400 dark:text-gray-500">→</span>
        <input 
            type="date"
            id="{{ $nameTo }}"
            name="{{ $nameTo }}"
            value="{{ $valueTo }}"
            class="flex-1 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 transition"
            placeholder="To"
        />
    </div>
</div>
