{{-- 
    SaaS Button Component
    
    Props:
    - type: primary|secondary|danger|ghost|outline (default: primary)
    - size: xs|sm|md|lg (default: md)
    - disabled: bool
    - loading: bool
    - icon: slot for icon element
    
    Usage:
    <x-button type="primary">Click Me</x-button>
    <x-button type="danger" size="sm">Delete</x-button>
--}}

@props([
    'type' => 'primary',
    'size' => 'md',
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $typeClasses = match($type) {
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'secondary' => 'bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-slate-50 hover:bg-slate-200 dark:hover:bg-slate-600 focus:ring-slate-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'ghost' => 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 focus:ring-slate-500',
        'outline' => 'border border-slate-300 dark:border-slate-600 text-slate-900 dark:text-slate-50 hover:bg-slate-50 dark:hover:bg-slate-900 focus:ring-slate-500',
        default => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
    };

    $sizeClasses = match($size) {
        'xs' => 'px-2.5 py-1.5 text-xs rounded-md',
        'sm' => 'px-3 py-1.5 text-sm rounded-md',
        'md' => 'px-4 py-2 text-sm rounded-lg',
        'lg' => 'px-5 py-2.5 text-base rounded-lg',
        default => 'px-4 py-2 text-sm rounded-lg',
    };
@endphp

<button {{ $attributes->merge(['class' => "{$baseClasses} {$typeClasses} {$sizeClasses}"]) }}>
    @if(isset($icon))
        <span class="mr-2">{{ $icon }}</span>
    @endif
    {{ $slot }}
</button>
