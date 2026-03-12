@props(['name', 'size' => '5'])

@php
    $icons = [
        'users' => '👥',
        'user' => '👤',
        'edit' => '✏️',
        'delete' => '🗑️',
        'add' => '➕',
        'view' => '👁️',
        'archive' => '📦',
        'restore' => '↩️',
        'report' => '📊',
        'settings' => '⚙️',
        'home' => '🏠',
        'back' => '←',
        'search' => '🔍',
        'filter' => '🔽',
        'download' => '⬇️',
        'upload' => '⬆️',
        'calendar' => '📅',
        'phone' => '📞',
        'mail' => '✉️',
        'location' => '📍',
        'check' => '✓',
        'cross' => '✗',
        'alert' => '⚠️',
        'success' => '✔️',
        'info' => 'ℹ️',
        'warning' => '⚠️',
        'error' => '❌',
    ];

    $icon = $icons[$name] ?? '•';
    $sizeClass = match($size) {
        '3' => 'text-lg',
        '4' => 'text-xl',
        '5' => 'text-2xl',
        '6' => 'text-3xl',
        default => 'text-base',
    };
@endphp

<span {{ $attributes->merge(['class' => $sizeClass]) }}>{{ $icon }}</span>
