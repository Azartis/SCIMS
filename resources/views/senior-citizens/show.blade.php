<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $seniorCitizen->getFormattedDisplayName() }}
            </h2>
            <a href="{{ route('senior-citizens.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                {{ __('Back to List') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                                {{ __('Basic Information') }}
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Last Name') }}</p>
                                    <p class="font-medium">{{ $seniorCitizen->lastname }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('First Name') }}</p>
                                    <p class="font-medium">{{ $seniorCitizen->firstname }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Middle Name') }}</p>
                                    <p class="font-medium">{{ $seniorCitizen->middlename ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Full Name') }}</p>
                                    <p class="font-medium italic text-blue-600 dark:text-blue-400">{{ $seniorCitizen->getFormattedDisplayName() }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Date of Birth') }}</p>
                                    <p class="font-medium">{{ $seniorCitizen->date_of_birth->format('F d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Age') }}</p>
                                    <p class="font-medium">{{ $seniorCitizen->age }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Gender') }}</p>
                                    <p class="font-medium">{{ $seniorCitizen->gender }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact & ID Information -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                                {{ __('Contact & ID') }}
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('OSCA ID') }}</p>
                                    <p class="font-medium">{{ $seniorCitizen->osca_id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Contact Number') }}</p>
                                    <p class="font-medium">{{ $seniorCitizen->contact_number ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Address') }}</p>
                                    <p class="font-medium">{{ $seniorCitizen->address }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Barangay') }}</p>
                                    <p class="font-medium">{{ $seniorCitizen->barangay ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pension / Membership Status -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        {{ __('Pension / Membership Type') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $seniorCitizen->sss ? '✓' : '✗' }}</span>
                            <span>{{ __('SSS') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $seniorCitizen->gsis ? '✓' : '✗' }}</span>
                            <span>{{ __('GSIS') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $seniorCitizen->pvao ? '✓' : '✗' }}</span>
                            <span>{{ __('PVAO') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $seniorCitizen->family_pension ? '✓' : '✗' }}</span>
                            <span>{{ __('Family Pension') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $seniorCitizen->brgy_official ? '✓' : '✗' }}</span>
                            <span>{{ __('Brgy Official') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        {{ __('Status') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $seniorCitizen->waitlist ? '✓' : '✗' }}</span>
                            <span>{{ __('Waitlist') }}</span>
                        </div>
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $seniorCitizen->social_pension ? '✓' : '✗' }}</span>
                            <span>{{ __('Social Pension') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            @if ($seniorCitizen->remarks)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                            {{ __('Remarks') }}
                        </h3>
                        <p>{{ $seniorCitizen->remarks }}</p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex gap-2 justify-end">
                <a href="{{ route('senior-citizens.edit', $seniorCitizen) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 dark:bg-yellow-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-yellow-700 dark:hover:bg-yellow-600 focus:bg-yellow-700 dark:focus:bg-yellow-600 active:bg-yellow-900 dark:active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    {{ __('Edit') }}
                </a>
                <form action="{{ route('senior-citizens.destroy', $seniorCitizen) }}" method="POST" style="display: inline;" onsubmit="return confirm('Archive this senior citizen? You can restore it later from the Archive section.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-100 uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-600 focus:bg-red-700 dark:focus:bg-red-600 active:bg-red-900 dark:active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Archive') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
