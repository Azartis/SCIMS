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
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search Bar -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                <form method="GET" action="{{ route('senior-citizens.archive') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Search -->
                        <div>
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Search by name or OSCA ID..." 
                                value="{{ request('search') }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm"
                            />
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-blue-700">
                            {{ __('Search') }}
                        </button>
                        <a href="{{ route('senior-citizens.archive') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-400">
                            {{ __('Reset') }}
                        </a>
                    </div>
                </form>
            </div>

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
