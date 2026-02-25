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

            <!-- Filter and Sort Bar -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <form method="GET" action="{{ route('reports.deceased') }}" class="p-4 flex flex-wrap gap-2 items-center">
                    <select name="barangay" class="px-2 py-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        <option value="">{{ __('All Barangays') }}</option>
                        @foreach($barangays as $b)
                            <option value="{{ $b }}" {{ request('barangay') === $b ? 'selected' : '' }}>
                                {{ $b }}
                            </option>
                        @endforeach
                    </select>

                    <select name="sex" class="px-2 py-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        <option value="">{{ __('Sex') }}</option>
                        <option value="Male" {{ request('sex') === 'Male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                        <option value="Female" {{ request('sex') === 'Female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                        <option value="Other" {{ request('sex') === 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                    </select>

                    <select name="age_range" class="px-2 py-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        <option value="">{{ __('All Ages') }}</option>
                        <option value="60-69" {{ request('age_range') === '60-69' ? 'selected' : '' }}>60-69</option>
                        <option value="70-79" {{ request('age_range') === '70-79' ? 'selected' : '' }}>70-79</option>
                        <option value="80+" {{ request('age_range') === '80+' ? 'selected' : '' }}>80+</option>
                    </select>

                    <select name="sort" class="px-2 py-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm">
                        <option value="asc" {{ request('sort') === 'asc' || !request('sort') ? 'selected' : '' }}>{{ __('A - Z') }}</option>
                        <option value="desc" {{ request('sort') === 'desc' ? 'selected' : '' }}>{{ __('Z - A') }}</option>
                    </select>

                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-md text-xs">{{ __('Filter') }}</button>

                    <a href="{{ route('reports.deceased') }}" class="px-3 py-1 bg-gray-500 text-white rounded-md text-xs">{{ __('Reset') }}</a>
                </form>
            </div>

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
                                        <td class="px-6 py-4">{{ $citizen->exact_age }}</td>
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