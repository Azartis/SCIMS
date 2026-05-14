<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Deceased / Archived Records') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">{{ __('Archived / Deceased') }}</h3>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $deceasedCount }}</p>
                </div>
            </div>

            <!-- Filter and Sort Bar (same UI as masterlist) -->
            <x-filter-bar
                :action="route('reports.deceased')"
                :resetUrl="route('reports.deceased')"
                :hasActiveFilters="request()->filled('search') || request()->filled('barangay') || request()->filled('sex') || request()->filled('age_range') || request()->filled('age_exact') || (request('sort') && request('sort') !== 'name_asc')"
                :activeCount="(request()->filled('search') ? 1 : 0) + (request()->filled('barangay') ? 1 : 0) + (request()->filled('sex') ? 1 : 0) + (request()->filled('age_range') || request()->filled('age_exact') ? 1 : 0) + (request('sort') && request('sort') !== 'name_asc' ? 1 : 0)"
            >
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Search') }}</label>
                    <input type="text" name="search" placeholder="{{ __('Name or OSCA ID') }}" value="{{ request('search') }}"
                        class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Barangay') }}</label>
                    <select name="barangay" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('All Barangays') }}</option>
                        @foreach($barangays as $b)
                            <option value="{{ $b }}" {{ request('barangay') === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">{{ __('Sex') }}</label>
                    <select name="sex" class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('All') }}</option>
                        <option value="Male" {{ request('sex') === 'Male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                        <option value="Female" {{ request('sex') === 'Female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                    </select>
                </div>
                <div>
                    <x-age-range-filter name="age_range" :value="request('age_range')" />
                </div>
                <div>
                    <x-sort-dropdown />
                </div>
            </x-filter-bar>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('List of Archived/Deceased Records') }}</h3>

                @if($seniorCitizens->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400">No archived records found.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-900 dark:text-gray-100">
                            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">{{ __('Full Name') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Age') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Sex') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Barangay') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Archived At') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($seniorCitizens as $citizen)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-medium">{{ $citizen->getFormattedDisplayName() }}</td>
                                        <td class="px-6 py-4">{{ $citizen->age }}</td>
                                        <td class="px-6 py-4">{{ $citizen->sex }}</td>
                                        <td class="px-6 py-4">{{ $citizen->barangay ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $citizen->deleted_at ? $citizen->deleted_at->format('M d, Y') : 'N/A' }}</td>
                                        <td class="px-6 py-4 flex gap-2">
                                            <a href="{{ route('reports.deceased.show', $citizen->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('View') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        {{ $seniorCitizens->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>