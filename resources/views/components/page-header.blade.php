@props(['title', 'subtitle' => null, 'icon' => null])

<div class="animate-slideUp">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div class="flex-1">
            <div class="flex items-center gap-4">
                @if($icon)
                    <div class="text-4xl">{{ $icon }}</div>
                @endif
                <div>
                    <h1 class="h2 text-gray-900 dark:text-white">{{ $title }}</h1>
                    @if($subtitle)
                        <p class="text-muted mt-2">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
        </div>
        @if(isset($actions))
            <div class="flex flex-wrap items-center gap-2 md:justify-end">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
