{{-- 
    SaaS Action Dropdown Component
    
    Standard CRUD action menu (View, Edit, Delete)
    
    Props:
    - id (mixed) - Resource ID
    - viewRoute (string) - View route name
    - editRoute (string) - Edit route name
    - deleteRoute (string) - Delete route name
    - showView: bool (default: true)
    - showEdit: bool (default: true)
    - showDelete: bool (default: true)
    
    Usage:
    <x-action-dropdown 
        :id="$senior->id"
        viewRoute="senior-citizens.show"
        editRoute="senior-citizens.edit"
        deleteRoute="senior-citizens.destroy"
    />
--}}

@props([
    'id' => null,
    'viewRoute' => null,
    'editRoute' => null,
    'deleteRoute' => null,
    'showView' => true,
    'showEdit' => true,
    'showDelete' => true,
])

<div class="relative inline-block" x-data="{ open: false }">
    <button 
        @click="open = !open" 
        @click.away="open = false"
        class="inline-flex items-center justify-center p-2 text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-md transition-colors"
        type="button"
    >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
        </svg>
    </button>

    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 z-10"
    >
        @if($showView && $viewRoute)
            <a href="{{ route($viewRoute, $id) }}" class="block px-4 py-2 text-sm text-slate-900 dark:text-slate-50 hover:bg-slate-100 dark:hover:bg-slate-700 first:rounded-t-lg transition-colors">
                View Details
            </a>
        @endif

        @if($showEdit && $editRoute)
            <a href="{{ route($editRoute, $id) }}" class="block px-4 py-2 text-sm text-slate-900 dark:text-slate-50 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                Edit
            </a>
        @endif

        @if($showDelete && $deleteRoute)
            <form method="POST" action="{{ route($deleteRoute, $id) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 last:rounded-b-lg transition-colors" onclick="return confirm('Are you sure? This action cannot be undone.')">
                    Delete
                </button>
            </form>
        @endif
    </div>
</div>
