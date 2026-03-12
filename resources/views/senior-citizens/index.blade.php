<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-start gap-4">
            <div>
                <x-page-header title="Senior Citizens Masterlist" subtitle="Browse and manage all registered senior citizens in the system" />
            </div>
            <a href="{{ route('senior-citizens.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 dark:bg-blue-700 text-white rounded-lg font-semibold hover:bg-blue-700 dark:hover:bg-blue-600 transition whitespace-nowrap">
                ➕ Add New
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">


        <!-- Search and Filter Panel -->
        <x-filter-bar
            :action="route('senior-citizens.index')"
            :resetUrl="route('senior-citizens.index')"
            :hasActiveFilters="request()->filled('search') || request()->filled('sex') || request()->filled('barangay') || request()->filled('social_pension') || request()->filled('pension_type') || request()->filled('age_range') || request()->filled('age_exact')"
            :activeCount="(optional($filterService)->getActiveFilterCount() ?? 0) + (request('sort') && request('sort') !== 'name_asc' ? 1 : 0)"
        >
            <div class="sm:col-span-2 md:col-span-2">
                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Search</label>
                <input type="text" name="search" placeholder="Name, OSCA ID, contact…" value="{{ request('search') }}"
                    class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Sex</label>
                <select name="sex" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="Male" {{ request('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                </select>
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
            <div>
                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Pension</label>
                <select name="social_pension" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">All</option>
                    <option value="1" {{ request('social_pension') === '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('social_pension') === '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Type</label>
                <select name="pension_type" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">All Types</option>
                    <option value="sss" {{ request('pension_type') === 'sss' ? 'selected' : '' }}>SSS</option>
                    <option value="gsis" {{ request('pension_type') === 'gsis' ? 'selected' : '' }}>GSIS</option>
                    <option value="pvao" {{ request('pension_type') === 'pvao' ? 'selected' : '' }}>PVAO</option>
                </select>
            </div>
            <div>
                <x-age-range-filter name="age_range" :value="request('age_range')" />
            </div>
            <div>
                <x-sort-dropdown />
            </div>
        </x-filter-bar>

        <!-- Results Summary -->
        @if(request()->filled('search') || request()->filled('sex') || request()->filled('barangay') || request()->filled('social_pension') || request()->filled('pension_type'))
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 md:p-4">
                <p class="text-xs md:text-sm text-blue-800 dark:text-blue-200">
                    Found <span class="font-semibold">{{ $seniorCitizens->total() }}</span> senior citizen(s) matching your filters
                </p>
            </div>
        @endif

        <!-- Data Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            @if ($seniorCitizens->isEmpty())
                <div class="p-8 text-center">
                    <p class="text-4xl mb-4">📭</p>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">No senior citizens found</p>
                    <p class="text-gray-500 dark:text-gray-500 mt-2 text-sm">
                        @if(request()->filled('search') || request()->filled('sex') || request()->filled('barangay'))
                            Try adjusting your filters
                        @else
                            <a href="{{ route('senior-citizens.create') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Add one now</a>
                        @endif
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-100">Name</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-100">Age</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-100">Barangay</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-100">Pension</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-100">Status</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-100">Contact</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-100">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($seniorCitizens as $citizen)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $citizen->getFormattedDisplayName() }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $citizen->osca_id }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-gray-100">{{ $citizen->age }}</td>
                                    <td class="px-6 py-4">
                                        <x-badge color="blue">{{ $citizen->barangay ?? 'N/A' }}</x-badge>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($citizen->pension_type)
                                            <x-badge color="green" variant="solid">{{ ucfirst(str_replace('_', ' ', $citizen->pension_type)) }}</x-badge>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($citizen->social_pension)
                                            <x-badge color="green">SocPen</x-badge>
                                        @endif
                                        @if($citizen->waitlist)
                                            <x-badge color="yellow">Waitlist</x-badge>
                                        @endif
                                        @if($citizen->with_disability)
                                            <x-badge color="purple">PWD</x-badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $citizen->contact_number ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('senior-citizens.show', $citizen) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-xs font-medium hover:bg-blue-200 dark:hover:bg-blue-900/50 transition">
                                                👁️ View
                                            </a>
                                            <a href="{{ route('senior-citizens.edit', $citizen) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300 rounded text-xs font-medium hover:bg-yellow-200 dark:hover:bg-yellow-900/50 transition">
                                                ✏️ Edit
                                            </a>
                                            @if($citizen->social_pension && !$citizen->date_of_death)
                                                <div x-data="{ open: false }" class="relative">
                                                    <button type="button" @click="open = !open" class="inline-flex items-center px-3 py-1.5 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 rounded text-xs font-medium hover:bg-orange-200 dark:hover:bg-orange-900/50 transition">
                                                        ☠️ Mark Deceased
                                                    </button>

                                                    <!-- Mark as Deceased Modal (with death details) -->
                                                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-700 rounded-lg shadow-xl z-50 p-6 border border-gray-200 dark:border-gray-600">
                                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">☠️ Mark as Deceased</h4>
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-4">Record death information for <strong>{{ $citizen->firstname }} {{ $citizen->lastname }}</strong> (Social Pension). This will be recorded in SPISC.</p>
                                                        
                                                        <form action="{{ route('senior-citizens.mark-deceased', $citizen) }}" method="POST" class="space-y-4">
                                                            @csrf
                                                            
                                                            <div>
                                                                <label class="form-label">Date of Death</label>
                                                                <input type="date" name="date_of_death" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-orange-500" required />
                                                            </div>
                                                            <div>
                                                                <label class="form-label">Cause of Death</label>
                                                                <input type="text" name="cause_of_death" placeholder="e.g., Natural causes, Accident, Illness..." class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-orange-500" required />
                                                            </div>
                                                            <div>
                                                                <label class="form-label">Death Certificate Registration #</label>
                                                                <input type="text" name="death_certificate_registration_number" placeholder="Certificate number" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-orange-500" required />
                                                            </div>

                                                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-3">
                                                                <p class="text-xs text-blue-700 dark:text-blue-300">
                                                                    <strong>ℹ️ Important:</strong> This recipient can only receive pension for the quarter of death in SPISC.
                                                                </p>
                                                            </div>

                                                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                                                                <button type="button" @click="open = false" class="px-4 py-2 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition">
                                                                    Cancel
                                                                </button>
                                                                <button type="submit" class="px-4 py-2 bg-orange-600 text-white font-medium hover:bg-orange-700 rounded-lg transition">
                                                                    Mark as Deceased
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endif
                                            <div x-data="{ open: false }" class="relative">
                                                <button type="button" @click="open = !open" class="inline-flex items-center px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded text-xs font-medium hover:bg-red-200 dark:hover:bg-red-900/50 transition">
                                                    📦 Archive
                                                </button>

                                                <!-- Archive Modal (Simple Confirmation) -->
                                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-700 rounded-lg shadow-xl z-50 p-6 border border-gray-200 dark:border-gray-600">
                                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">📦 Archive Senior Citizen</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Are you sure you want to archive <strong>{{ $citizen->firstname }} {{ $citizen->lastname }}</strong>? This action can be undone from the Archive section.</p>
                                                    
                                                    <form action="{{ route('senior-citizens.destroy', $citizen) }}" method="POST" class="space-y-4">
                                                        @csrf
                                                        @method('DELETE')

                                                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-600">
                                                            <button type="button" @click="open = false" class="px-4 py-2 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition">
                                                                Cancel
                                                            </button>
                                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white font-medium hover:bg-red-700 rounded-lg transition">
                                                                Confirm Archive
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between bg-gray-50 dark:bg-gray-700/50">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Showing <span class="font-semibold">{{ $seniorCitizens->firstItem() }}</span> to <span class="font-semibold">{{ $seniorCitizens->lastItem() }}</span> of <span class="font-semibold">{{ $seniorCitizens->total() }}</span>
                    </div>
                    <div>
                        {{ $seniorCitizens->links() }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Access Links -->
        <div class="flex gap-3 justify-center">
            <a href="{{ route('senior-citizens.archive') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                📦 View Archived
            </a>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-green-200 dark:bg-green-900/30 text-green-900 dark:text-green-300 rounded-lg font-medium hover:bg-green-300 dark:hover:bg-green-900/50 transition">
                📊 Generate Reports
            </a>
        </div>
    </div>
</x-app-layout>
