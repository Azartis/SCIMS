<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reports & Statistics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Senior Citizens -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">{{ __('Total Senior Citizens') }}</h3>
                        <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $totalSeniorCitizens }}</p>
                    </div>
                </div>

                <!-- Waitlist Count -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">{{ __('Waitlist') }}</h3>
                        <p class="text-4xl font-bold text-yellow-600 dark:text-yellow-400">{{ $waitlistCount }}</p>
                    </div>
                </div>

                <!-- Social Pension Count -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-semibold mb-2">{{ __('Social Pension') }}</h3>
                        <p class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $socialPensionCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Pension Types -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Pension Types') }}</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span>{{ __('SSS') }}</span>
                                <span class="font-bold">{{ $sssCount }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('GSIS') }}</span>
                                <span class="font-bold">{{ $gsisCount }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('PVAO') }}</span>
                                <span class="font-bold">{{ $pvaoCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Gender Distribution') }}</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span>{{ __('Male') }}</span>
                                <span class="font-bold">{{ $maleCount }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('Female') }}</span>
                                <span class="font-bold">{{ $femaleCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export and View Options -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">{{ __('Export Options') }}</h3>
                
                <form method="GET" action="{{ route('reports.export') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Gender Filter -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">{{ __('Filter by Gender (Optional)') }}</label>
                            <select name="gender" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
                                <option value="">All Genders</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">{{ __('Filter by Status (Optional)') }}</label>
                            <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
                                <option value="">All Status</option>
                                <option value="waitlist">Waitlist</option>
                                <option value="social_pension">Social Pension</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-green-700">
                            {{ __('Export to CSV') }}
                        </button>
                        <a href="{{ route('reports.statistics') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-blue-700">
                            {{ __('View Detailed Statistics') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
