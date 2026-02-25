<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Barangay Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                @foreach($barangays as $b)
                    <a href="{{ route('reports.barangay', ['barangay' => $b]) }}" class="block bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:border-blue-500 border border-transparent">
                        <h3 class="text-lg font-semibold mb-2">{{ $b }}</h3>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $counts[$b] ?? 0 }}</p>
                    </a>
                @endforeach
            </div>

            @if(isset($selected) && $seniorCitizens)
                <x-modal name="barangayModal" :show="isset($selected)" focusable>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Residents of {{ $selected }}</h3>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('reports.barangay') }}" class="px-3 py-1 text-xs bg-gray-200 dark:bg-gray-700 rounded">Close</a>
                            </div>
                        </div>

                        <!-- Filters and export -->
                        <form method="GET" action="{{ route('reports.barangay') }}" class="mb-4 flex flex-wrap gap-2 items-center">
                            <input type="hidden" name="barangay" value="{{ $selected }}">

                            <input type="text" name="search" placeholder="Search name or OSCA ID" value="{{ request('search') }}" class="px-2 py-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm" />

                            <select name="sex" class="px-2 py-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                                <option value="">Sex</option>
                                <option value="Male" {{ request('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ request('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ request('sex') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>

                            <select name="age_range" class="px-2 py-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                                <option value="">Age Range</option>
                                <option value="60-69" {{ request('age_range') === '60-69' ? 'selected' : '' }}>60-69</option>
                                <option value="70-79" {{ request('age_range') === '70-79' ? 'selected' : '' }}>70-79</option>
                                <option value="80+" {{ request('age_range') === '80+' ? 'selected' : '' }}>80+</option>
                            </select>

                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-md text-xs">Filter</button>

                            <a href="{{ route('reports.barangay.export', request()->query()) }}" class="px-3 py-1 bg-gray-800 text-white rounded-md text-xs">Export CSV</a>
                        </form>

                        @if($seniorCitizens->isEmpty())
                            <p class="text-gray-600 dark:text-gray-400">No records found in this barangay.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-900 dark:text-gray-100">
                                    <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                        <tr>
                                            <th class="px-6 py-3 font-semibold">{{ __('Full Name') }}</th>
                                            <th class="px-6 py-3 font-semibold">{{ __('Age') }}</th>
                                            <th class="px-6 py-3 font-semibold">{{ __('Sex') }}</th>
                                            <th class="px-6 py-3 font-semibold">{{ __('OSCA ID') }}</th>
                                            <th class="px-6 py-3 font-semibold">{{ __('Contact') }}</th>
                                            <th class="px-6 py-3 font-semibold">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                        @foreach($seniorCitizens as $citizen)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 font-medium">{{ $citizen->getFormattedDisplayName() }}</td>
                                                <td class="px-6 py-4">{{ $citizen->exact_age }}</td>
                                                <td class="px-6 py-4">{{ $citizen->sex }}</td>
                                                <td class="px-6 py-4">{{ $citizen->osca_id }}</td>
                                                <td class="px-6 py-4">{{ $citizen->contact_number ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 flex gap-2">
                                                    <a href="{{ route('senior-citizens.show', $citizen) }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('View') }}</a>
                                                    <a href="{{ route('senior-citizens.edit', $citizen) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline">{{ __('Edit') }}</a>
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
                </x-modal>
            @endif
        </div>
    </div>
</x-app-layout>