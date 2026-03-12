@props(['icon' => null, 'color' => 'blue', 'gradient' => false])

@php
    $bgColor = match($color) {
        'blue' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800',
        'green' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800',
        'red' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800',
        'yellow' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800',
        'purple' => 'bg-purple-50 dark:bg-purple-900/20 border-purple-200 dark:border-purple-800',
        'teal' => 'bg-teal-50 dark:bg-teal-900/20 border-teal-200 dark:border-teal-800',
        default => 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700',
    };

    $textColor = match($color) {
        'blue' => 'text-blue-600 dark:text-blue-400',
        'green' => 'text-green-600 dark:text-green-400',
        'red' => 'text-red-600 dark:text-red-400',
        'yellow' => 'text-yellow-600 dark:text-yellow-400',
        'purple' => 'text-purple-600 dark:text-purple-400',
        'teal' => 'text-teal-600 dark:text-teal-400',
        default => 'text-gray-600 dark:text-gray-400',
    };

    $gradientClass = $gradient ? 'bg-gradient-to-br dark:from-opacity-20 dark:to-opacity-10 shadow-md hover:shadow-lg transition-shadow' : '';
@endphp

<div {{ $attributes->merge(['class' => "border rounded-lg p-6 $bgColor $gradientClass"]) }}>
    <div class="flex items-center justify-between">
        <div>{{ $slot }}</div>
        @if($icon)
            <div class="{{ $textColor }} text-3xl">{{ $icon }}</div>
        @endif
    </div>
</div>
