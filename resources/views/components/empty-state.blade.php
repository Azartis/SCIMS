{{-- 
    Empty State Component
    
    Shown when no data is available
    
    Props:
    - icon: slot for icon
    - title (string) - Main message
    - description (string) - Supporting text
    - action (slot) - CTA button
    
    Usage:
    <x-empty-state title="No seniors found" description="Create your first senior citizen record to get started.">
        <x-slot name="action">
            <a href="{{ route('senior-citizens.create') }}" class="btn btn-primary">
                Create New
            </a>
        </x-slot>
    </x-empty-state>
--}}

@props([
    'title' => 'No data available',
    'description' => '',
])

<div class="text-center py-12 px-6">
    <div class="mb-4">
        @if(isset($icon))
            <div class="flex justify-center text-4xl opacity-20">
                {{ $icon }}
            </div>
        @else
            <svg class="mx-auto h-12 w-12 text-slate-400 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
        @endif
    </div>

    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50 mb-2">{{ $title }}</h3>
    
    @if($description)
        <p class="text-slate-500 dark:text-slate-400 mb-6">{{ $description }}</p>
    @endif

    @if(isset($action))
        <div>{{ $action }}</div>
    @endif
</div>
