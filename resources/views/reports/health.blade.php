<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Health Condition Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                @foreach($conditions as $key => $label)
                    <a href="{{ route('reports.health', ['condition' => $key]) }}" class="block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:border-blue-500 border border-transparent">
                        <h3 class="text-lg font-semibold mb-2">{{ $label }}</h3>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $counts[$key] ?? 0 }}</p>
                    </a>
                @endforeach
            </div>

            @if(isset($condition) && $seniorCitizens)
                <!-- Modal for Health Condition Results -->
                <x-modal name="healthModal" :show="isset($condition)" focusable>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">{{ $conditions[$condition] ?? 'Results' }}</h3>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('reports.health') }}" class="px-3 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded">{{ __('Close') }}</a>
                            </div>
                        </div>

                        <!-- Unified Filter Bar -->
                        <div x-data="{ open: {{ request()->filled('search') || request()->filled('barangay') || request()->filled('sex') || request()->filled('age_range') || request()->filled('age_exact') ? 'true' : 'false' }} }" class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <button type="button" @click="open = !open" class="flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 transition-colors">
                                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <span class="text-xs md:text-sm">🔍 Filters</span>
                                </button>
                                <a href="{{ route('reports.health', ['condition' => $condition]) }}" class="text-xs md:text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition">↻ Reset</a>
                            </div>
                            
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-3 md:p-4 shadow-sm">
                                <form method="GET" action="{{ route('reports.health') }}" class="space-y-3">
                                    <input type="hidden" name="condition" value="{{ $condition }}">
                                    
                                    <!-- Main Filters -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Search</label>
                                            <input type="text" name="search" placeholder="Name or OSCA ID" value="{{ request('search') }}" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" />
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Barangay</label>
                                            <select name="barangay" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                                                <option value="">All Barangays</option>
                                                @foreach(\App\Constants\Barangay::list() as $barangay)
                                                    <option value="{{ $barangay }}" {{ request('barangay') === $barangay ? 'selected' : '' }}>{{ $barangay }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Condition Specific Filters --}}
                                        @if($condition === 'with_disability')
                                            <div>
                                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Disability Type</label>
                                                <select name="type_of_disability" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                                                    <option value="">All Types</option>
                                                    @foreach(['Deaf','Intellectual Disability','Learning Disability','Mental Disability','Physical Disability (Orthopedic)','Psychosocial Disability','Speech and Language Impairment','Visual Disability','Cancer(RA11215)','Rare Disease(RA10747)'] as $type)
                                                        <option value="{{ $type }}" {{ request('type_of_disability') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        @if($condition === 'with_assistive_device')
                                            <div>
                                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Device Type</label>
                                                <input type="text" name="type_of_assistive_device" placeholder="Device type" value="{{ request('type_of_assistive_device') }}" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" />
                                            </div>
                                        @endif

                                        @if($condition === 'with_critical_illness')
                                            <div>
                                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Illness</label>
                                                <input type="text" name="specify_illness" placeholder="Specify illness" value="{{ request('specify_illness') }}" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" />
                                            </div>
                                        @endif

                                        @if($condition === 'philhealth_member')
                                            <div>
                                                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">PhilHealth ID</label>
                                                <input type="text" name="philhealth_id" placeholder="PhilHealth ID" value="{{ request('philhealth_id') }}" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" />
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Sex</label>
                                            <select name="sex" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                                                <option value="">All</option>
                                                <option value="Male" {{ request('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ request('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <x-age-range-filter name="age_range" :value="request('age_range')" />
                                        </div>

                                        <div>
                                            <x-sort-dropdown />
                                        </div>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-200 dark:border-slate-700">
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 dark:bg-blue-600 text-white text-xs md:text-sm font-semibold rounded-md hover:bg-blue-700 dark:hover:bg-blue-500 transition">
                                            <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                            <span class="hidden sm:inline">Apply</span>
                                        </button>
                                        <a href="{{ route('reports.health.export', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-700 dark:bg-gray-800 text-white text-xs md:text-sm font-medium rounded-md hover:bg-gray-800 dark:hover:bg-gray-700 transition">
                                            📥 <span class="hidden sm:inline">Export CSV</span>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Results Table -->
                        @if($seniorCitizens->isEmpty())
                            <p class="text-gray-600 dark:text-gray-400 py-6">{{ __('No records found for this condition.') }}</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-900 dark:text-gray-100">
                                    <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <tr>
                                            <th class="px-4 py-3 font-semibold">{{ __('Full Name') }}</th>
                                            <th class="px-4 py-3 font-semibold">{{ __('Age') }}</th>
                                            <th class="px-4 py-3 font-semibold">{{ __('Sex') }}</th>
                                            <th class="px-4 py-3 font-semibold">{{ __('Barangay') }}</th>
                                            <th class="px-4 py-3 font-semibold">{{ __('OSCA ID') }}</th>
                                            @if($condition === 'with_disability')
                                                <th class="px-4 py-3 font-semibold">{{ __('Disability Type') }}</th>
                                            @elseif($condition === 'with_assistive_device')
                                                <th class="px-4 py-3 font-semibold">{{ __('Device') }}</th>
                                            @elseif($condition === 'with_critical_illness')
                                                <th class="px-4 py-3 font-semibold">{{ __('Illness') }}</th>
                                            @elseif($condition === 'philhealth_member')
                                                <th class="px-4 py-3 font-semibold">{{ __('PHIC ID') }}</th>
                                            @endif
                                            <th class="px-4 py-3 font-semibold">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                        @foreach($seniorCitizens as $citizen)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-4 py-3 font-medium">{{ $citizen->getFormattedDisplayName() }}</td>
                                                <td class="px-4 py-3">{{ $citizen->age }}</td>
                                                <td class="px-4 py-3">{{ $citizen->sex }}</td>
                                                <td class="px-4 py-3">{{ $citizen->barangay ?? 'N/A' }}</td>
                                                <td class="px-4 py-3">{{ $citizen->osca_id }}</td>
                                                @if($condition === 'with_disability')
                                                    <td class="px-4 py-3">{{ $citizen->type_of_disability ?? '-' }}</td>
                                                @elseif($condition === 'with_assistive_device')
                                                    <td class="px-4 py-3">{{ $citizen->type_of_assistive_device ?? '-' }}</td>
                                                @elseif($condition === 'with_critical_illness')
                                                    <td class="px-4 py-3">{{ $citizen->specify_illness ?? '-' }}</td>
                                                @elseif($condition === 'philhealth_member')
                                                    <td class="px-4 py-3">{{ $citizen->philhealth_id ?? '-' }}</td>
                                                @endif
                                                <td class="px-4 py-3 flex gap-2">
                                                    <a href="{{ route('senior-citizens.show', $citizen) }}" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">{{ __('View') }}</a>
                                                    <a href="{{ route('senior-citizens.edit', $citizen) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline text-sm">{{ __('Edit') }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-600">
                                {{ $seniorCitizens->links() }}
                            </div>
                        @endif
                    </div>

                    <x-slot name="footer">
                        <a href="{{ route('reports.health') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400">
                            {{ __('Back to Categories') }}
                        </a>
                    </x-slot>
                </x-modal>
            @endif
        </div>
    </div>
</x-app-layout>