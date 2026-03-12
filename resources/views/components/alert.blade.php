@props(['type' => 'info', 'closable' => true])

@php
    $classes = match($type) {
        'success' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
        'error' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-200',
        'warning' => 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200',
        default => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
    };
@endphp

<div {{ $attributes->merge(['class' => "border-l-4 rounded p-4 $classes"]) }}>
    <div class="flex justify-between items-start gap-4">
        <div>{{ $slot }}</div>
        @if($closable)
            <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-lg opacity-60 hover:opacity-100">×</button>
        @endif
    </div>
</div>
