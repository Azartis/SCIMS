{{-- 
    Loading Spinner Component
    
    Generic loading indicator
    
    Props:
    - size: xs|sm|md|lg (default: md)
    - color: blue|slate|gray (default: blue)
    - text (string) - Optional loading text
    
    Usage:
    <x-loading-spinner size="lg" color="blue" text="Loading data..." />
--}}

@props([
    'size' => 'md',
    'color' => 'blue',
    'text' => '',
])

@php
    $sizeClasses = match($size) {
        'xs' => 'w-4 h-4',
        'sm' => 'w-6 h-6',
        'md' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        default => 'w-8 h-8',
    };

    $colorClass = match($color) {
        'blue' => 'text-blue-600 dark:text-blue-400',
        'slate' => 'text-slate-600 dark:text-slate-400',
        'gray' => 'text-gray-600 dark:text-gray-400',
        default => 'text-blue-600 dark:text-blue-400',
    };
@endphp

<div class="flex flex-col items-center justify-center {{ $text ? 'space-y-3' : '' }}">
    <svg class="animate-spin {{ $sizeClasses }} {{ $colorClass }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    
    @if($text)
        <p class="text-sm text-slate-600 dark:text-slate-400">{{ $text }}</p>
    @endif
</div>
