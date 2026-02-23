<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Senior Citizens Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Senior Citizens Management') }}</h3>
                        <p class="mb-4 text-gray-600 dark:text-gray-400">
                            {{ __('Manage and track senior citizen information including personal details, pension types, and registration status.') }}
                        </p>
                        <a href="{{ route('senior-citizens.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-blue-700">
                            {{ __('Manage') }}
                        </a>
                    </div>
                </div>

                <!-- Reports Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Reports & Statistics') }}</h3>
                        <p class="mb-4 text-gray-600 dark:text-gray-400">
                            {{ __('Generate reports, export data to CSV, and view statistics on senior citizens.') }}
                        </p>
                        <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-green-700">
                            {{ __('View Reports') }}
                        </a>
                    </div>
                </div>

                <!-- User Management Card (Admin Only) -->
                @if(auth()->user()->role === 'admin')
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="text-lg font-semibold mb-4">{{ __('User Management') }}</h3>
                            <p class="mb-4 text-gray-600 dark:text-gray-400">
                                {{ __('Manage admin and staff user accounts, assign roles, and control access.') }}
                            </p>
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-red-700">
                                {{ __('Manage Users') }}
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Quick Stats Card -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Quick Stats') }}</h3>
                        <div class="space-y-2">
                            <p class="text-sm">
                                <span class="font-semibold">Total Senior Citizens:</span> 
                                <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ \App\Models\SeniorCitizen::count() }}</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Click on Senior Citizens Management to view all records.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
