{{-- 
    SaaS Table Component
    
    Renders a professional data table with pagination
    
    Props:
    - headers (array) - Column headers
    - rows (Paginator|Collection) - Data rows
    - actions (slot) - Action buttons for each row
    - allowSort: bool
    - allowSelect: bool
    
    Usage:
    <x-table :headers="['Name', 'Email', 'Status']" :rows="$users">
        {{ $slot }}
    </x-table>
--}}

@props([
    'headers' => [],
    'rows' => null,
    'allowSort' => false,
    'allowSelect' => false,
])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
        <thead class="bg-slate-50 dark:bg-slate-900">
            <tr>
                @if($allowSelect)
                    <th class="px-6 py-3 text-left">
                        <input type="checkbox" class="rounded" />
                    </th>
                @endif
                
                @foreach($headers as $header)
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        @if($allowSort)
                            <a href="#" class="hover:text-slate-900 dark:hover:text-slate-100">
                                {{ $header }}
                            </a>
                        @else
                            {{ $header }}
                        @endif
                    </th>
                @endforeach
                
                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse($rows as $row)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    @if($allowSelect)
                        <td class="px-6 py-3">
                            <input type="checkbox" class="rounded" value="{{ $row->id ?? '' }}" />
                        </td>
                    @endif
                    
                    {{ $slot }}
                    
                    @if(isset($actions))
                        <td class="px-6 py-3 text-right text-sm">
                            {{ $actions }}
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) + ($allowSelect ? 2 : 1) }}" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">
                        No records found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($rows && method_exists($rows, 'links'))
    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
        {{ $rows->links() }}
    </div>
@endif
