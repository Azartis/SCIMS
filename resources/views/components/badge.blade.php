@props(['color' => 'gray', 'variant' => 'solid'])

@php
    $solidColors = [
        'blue' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200',
        'green' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200',
        'red' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200',
        'yellow' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200',
        'purple' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200',
        'gray' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200',
    ];

    $outlineColors = [
        'blue' => 'border border-blue-300 dark:border-blue-700 text-blue-700 dark:text-blue-300',
        'green' => 'border border-green-300 dark:border-green-700 text-green-700 dark:text-green-300',
        'red' => 'border border-red-300 dark:border-red-700 text-red-700 dark:text-red-300',
        'yellow' => 'border border-yellow-300 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300',
        'purple' => 'border border-purple-300 dark:border-purple-700 text-purple-700 dark:text-purple-300',
        'gray' => 'border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300',
    ];

    $classes = $variant === 'outline' ? $outlineColors[$color] : $solidColors[$color];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap $classes"]) }}>
    {{ $slot }}
</span>
