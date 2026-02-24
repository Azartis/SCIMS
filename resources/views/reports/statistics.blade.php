<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detailed Statistics') }}
            </h2>
            <a href="{{ route('reports.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                {{ __('Back to Reports') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Pension Statistics -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
                        {{ __('Pension Type Breakdown') }}
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-lg">SSS</span>
                            <div class="flex items-center gap-4">
                                <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $pensionStats['sss'] }}</span>
                                <div class="w-40 bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                    <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $pensionStats['sss'] > 0 ? min(($pensionStats['sss'] / max($pensionStats['sss'], $pensionStats['gsis'], $pensionStats['pvao'], $pensionStats['family_pension'], $pensionStats['brgy_official']) * 100), 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-lg">GSIS</span>
                            <div class="flex items-center gap-4">
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $pensionStats['gsis'] }}</span>
                                <div class="w-40 bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                    <div class="bg-green-600 h-4 rounded-full" style="width: {{ $pensionStats['gsis'] > 0 ? min(($pensionStats['gsis'] / max($pensionStats['sss'], $pensionStats['gsis'], $pensionStats['pvao'], $pensionStats['family_pension'], $pensionStats['brgy_official']) * 100), 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-lg">PVAO</span>
                            <div class="flex items-center gap-4">
                                <span class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pensionStats['pvao'] }}</span>
                                <div class="w-40 bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                    <div class="bg-yellow-600 h-4 rounded-full" style="width: {{ $pensionStats['pvao'] > 0 ? min(($pensionStats['pvao'] / max($pensionStats['sss'], $pensionStats['gsis'], $pensionStats['pvao'], $pensionStats['family_pension'], $pensionStats['brgy_official']) * 100), 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pb-4 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-lg">Family Pension</span>
                            <div class="flex items-center gap-4">
                                <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $pensionStats['family_pension'] }}</span>
                                <div class="w-40 bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                    <div class="bg-purple-600 h-4 rounded-full" style="width: {{ $pensionStats['family_pension'] > 0 ? min(($pensionStats['family_pension'] / max($pensionStats['sss'], $pensionStats['gsis'], $pensionStats['pvao'], $pensionStats['family_pension'], $pensionStats['brgy_official']) * 100), 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-lg">Brgy Official</span>
                            <div class="flex items-center gap-4">
                                <span class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $pensionStats['brgy_official'] }}</span>
                                <div class="w-40 bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                                    <div class="bg-red-600 h-4 rounded-full" style="width: {{ $pensionStats['brgy_official'] > 0 ? min(($pensionStats['brgy_official'] / max($pensionStats['sss'], $pensionStats['gsis'], $pensionStats['pvao'], $pensionStats['family_pension'], $pensionStats['brgy_official']) * 100), 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sex Distribution -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
                        {{ __('Sex Distribution') }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ $sexStats['male'] }}</div>
                            <p class="text-gray-600 dark:text-gray-400">Male</p>
                            <div class="mt-3 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $sexStats['male'] > 0 ? min(($sexStats['male'] / max($sexStats['male'], $sexStats['female'], $sexStats['other']) * 100), 100) : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="text-4xl font-bold text-pink-600 dark:text-pink-400 mb-2">{{ $sexStats['female'] }}</div>
                            <p class="text-gray-600 dark:text-gray-400">Female</p>
                            <div class="mt-3 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-pink-600 h-2 rounded-full" style="width: {{ $sexStats['female'] > 0 ? min(($sexStats['female'] / max($sexStats['male'], $sexStats['female'], $sexStats['other']) * 100), 100) : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="text-4xl font-bold text-gray-600 dark:text-gray-400 mb-2">{{ $sexStats['other'] }}</div>
                            <p class="text-gray-600 dark:text-gray-400">Other</p>
                            <div class="mt-3 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gray-600 h-2 rounded-full" style="width: {{ $sexStats['other'] > 0 ? min(($sexStats['other'] / max($sexStats['male'], $sexStats['female'], $sexStats['other']) * 100), 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold mb-6 border-b border-gray-200 dark:border-gray-700 pb-3">
                        {{ __('Status Summary') }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">{{ __('On Waitlist') }}</p>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $statusStats['waitlist'] }}</p>
                        </div>

                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-2">{{ __('Receiving Social Pension') }}</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $statusStats['social_pension'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
