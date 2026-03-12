@props(['items'])

<div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
    <div class="text-sm text-gray-600 dark:text-gray-400">
        Showing <span class="font-semibold">{{ $items->firstItem() ?? 0 }}</span> to <span class="font-semibold">{{ $items->lastItem() ?? 0 }}</span> of <span class="font-semibold">{{ $items->total() }}</span> results
    </div>
    <div>
        {{ $items->links() }}
    </div>
</div>
