<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Archived Senior Citizens') }}
            </h2>
            <a href="{{ route('senior-citizens.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                {{ __('Back to Active Records') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <x-filter-bar
                :action="route('senior-citizens.archive')"
                :resetUrl="route('senior-citizens.archive')"
                :hasActiveFilters="request()->filled('search') || request()->filled('age_range') || request()->filled('age_exact') || (request('sort') && request('sort') !== 'name_asc')"
                :activeCount="(request()->filled('search') ? 1 : 0) + (request()->filled('age_range') || request()->filled('age_exact') ? 1 : 0) + (request('sort') && request('sort') !== 'name_asc' ? 1 : 0)"
            >
                <div class="sm:col-span-2 md:col-span-2">
                    <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Search</label>
                    <input type="text" name="search" placeholder="Name or OSCA ID" value="{{ request('search') }}"
                        class="w-full px-2.5 py-1.5 text-xs md:text-sm rounded-md border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 focus:ring-2 focus:ring-blue-500" />
                </div>
                <div>
                    <x-age-range-filter name="age_range" :value="request('age_range')" />
                </div>
                <div>
                    <x-sort-dropdown :options="['name_asc' => 'Name A → Z', 'name_desc' => 'Name Z → A']" />
                </div>
            </x-filter-bar>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if ($archivedCitizens->isEmpty())
                    <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
                        {{ __('No archived senior citizens found.') }}
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-900 dark:text-gray-100">
                            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th class="px-6 py-3 font-semibold">{{ __('Full Name') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Age') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Sex') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('OSCA ID') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Barangay') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Date of Death') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Cause') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Cert #') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Archived Date') }}</th>
                                    <th class="px-6 py-3 font-semibold">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach ($archivedCitizens as $citizen)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 font-medium">{{ $citizen->getFormattedDisplayName() }}</td>
                                        <td class="px-6 py-4">{{ $citizen->age }}</td>
                                        <td class="px-6 py-4">{{ $citizen->sex }}</td>
                                        <td class="px-6 py-4">{{ $citizen->osca_id }}</td>
                                        <td class="px-6 py-4">{{ $citizen->barangay }}</td>
                                        <td class="px-6 py-4">{{ optional($citizen->date_of_death)->format('M d, Y') }}</td>
                                        <td class="px-6 py-4">{{ $citizen->cause_of_death ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $citizen->death_certificate_number ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">{{ $citizen->deleted_at->format('M d, Y H:i') }}</td>
                                        <td class="px-6 py-4 flex gap-2">
                                            <form action="{{ route('senior-citizens.restore', $citizen->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="text-green-600 dark:text-green-400 hover:underline">{{ __('Restore') }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        {{ $archivedCitizens->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
