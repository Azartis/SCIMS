@props(['title' => null, 'icon' => null])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6']) }}>
    @if($title)
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            @if($icon)
                <span class="text-2xl">{{ $icon }}</span>
            @endif
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
        </div>
    @endif
    {{ $slot }}
</div>
