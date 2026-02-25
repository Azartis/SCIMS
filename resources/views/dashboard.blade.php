<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Masterlist Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold mb-4">Masterlist</h3>
                            <p class="mb-4 text-gray-600 dark:text-gray-400">Master list of senior citizens</p>
                            <a href="{{ route('senior-citizens.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-blue-700">View Masterlist</a>
                        </div>
                    </div>

                    <!-- SPISC Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold mb-4">SPISC</h3>
                            <p class="mb-4 text-gray-600 dark:text-gray-400">SPISC management and reports</p>
                            <a href="{{ route('spisc.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-gray-700">Open SPISC</a>
                        </div>
                    </div>

                    <div class="hidden md:block"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Reports Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Reports & Statistics') }}</h3>
                            <p class="mb-4 text-gray-600 dark:text-gray-400">{{ __('Generate reports, export data to CSV, and view statistics on senior citizens.') }}</p>
                            <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-green-700">{{ __('View Reports') }}</a>
                        </div>
                    </div>

                    <!-- History Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Change History') }}</h3>
                            <p class="mb-4 text-gray-600 dark:text-gray-400">{{ __('View a log of all changes made to senior citizen records.') }}</p>
                            <a href="{{ route('history') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 dark:bg-yellow-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-yellow-700">{{ __('View History') }}</a>
                        </div>
                    </div>

                    <!-- Quick Stats Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Quick Stats') }}</h3>
                            <div class="space-y-2">
                                <p class="text-sm">
                                    <span class="font-semibold">Total Senior Citizens:</span>
                                    <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ \App\Models\SeniorCitizen::count() }}</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Click on Masterlist to view all records.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
