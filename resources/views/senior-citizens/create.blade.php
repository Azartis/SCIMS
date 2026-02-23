<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Senior Citizen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('senior-citizens.store') }}" method="POST">
                        @csrf

                        <!-- Name Section -->
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">{{ __('Full Name') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Last Name -->
                                <div>
                                    <x-input-label for="lastname" :value="__('Last Name *')" />
                                    <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" required autofocus />
                                    <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                                </div>

                                <!-- First Name -->
                                <div>
                                    <x-input-label for="firstname" :value="__('First Name *')" />
                                    <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname" :value="old('firstname')" required />
                                    <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                </div>

                                <!-- Middle Name -->
                                <div>
                                    <x-input-label for="middlename" :value="__('Middle Name')" />
                                    <x-text-input id="middlename" class="block mt-1 w-full" type="text" name="middlename" :value="old('middlename')" />
                                    <x-input-error :messages="$errors->get('middlename')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Date of Birth & Age -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                                <x-text-input id="date_of_birth" class="block mt-1 w-full" type="date" name="date_of_birth" :value="old('date_of_birth')" required />
                                <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="age" :value="__('Age')" />
                                <x-text-input id="age" class="block mt-1 w-full" type="number" name="age" :value="old('age')" required />
                                <x-input-error :messages="$errors->get('age')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Gender & Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="gender" :value="__('Gender')" />
                                <select id="gender" name="gender" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm" required>
                                    <option value="">{{ __('Select Gender') }}</option>
                                    <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                    <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="contact_number" :value="__('Contact Number')" />
                                <x-text-input id="contact_number" class="block mt-1 w-full" type="text" name="contact_number" :value="old('contact_number')" />
                                <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Location Section: Barangay & Address -->
                        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Barangay -->
                            <div>
                                <x-input-label for="barangay" :value="__('Barangay *')" />
                                <select id="barangay" name="barangay" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm" required>
                                    <option value="">{{ __('Select Barangay') }}</option>
                                    @foreach(\App\Constants\Barangay::list() as $barangay)
                                        <option value="{{ $barangay }}" {{ old('barangay') === $barangay ? 'selected' : '' }}>
                                            {{ $barangay }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('barangay')" class="mt-2" />
                            </div>

                            <!-- Address -->
                            <div>
                                <x-input-label for="address" :value="__('Address *')" />
                                <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required />
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>

                        <!-- OSCA ID -->
                        <div class="mb-6">
                            <x-input-label for="osca_id" :value="__('OSCA ID')" />
                            <x-text-input id="osca_id" class="block mt-1 w-full" type="text" name="osca_id" :value="old('osca_id')" required />
                            <x-input-error :messages="$errors->get('osca_id')" class="mt-2" />
                        </div>

                        <!-- Pension / Membership Type -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Pension / Membership Type') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="sss" value="1" {{ old('sss') ? 'checked' : '' }} class="rounded dark:bg-gray-700 dark:border-gray-600" />
                                    <span class="ml-2">{{ __('SSS') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="gsis" value="1" {{ old('gsis') ? 'checked' : '' }} class="rounded dark:bg-gray-700 dark:border-gray-600" />
                                    <span class="ml-2">{{ __('GSIS') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="pvao" value="1" {{ old('pvao') ? 'checked' : '' }} class="rounded dark:bg-gray-700 dark:border-gray-600" />
                                    <span class="ml-2">{{ __('PVAO') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="family_pension" value="1" {{ old('family_pension') ? 'checked' : '' }} class="rounded dark:bg-gray-700 dark:border-gray-600" />
                                    <span class="ml-2">{{ __('Family Pension') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="brgy_official" value="1" {{ old('brgy_official') ? 'checked' : '' }} class="rounded dark:bg-gray-700 dark:border-gray-600" />
                                    <span class="ml-2">{{ __('Brgy Official') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Status Fields -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Status') }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="waitlist" value="1" {{ old('waitlist') ? 'checked' : '' }} class="rounded dark:bg-gray-700 dark:border-gray-600" />
                                    <span class="ml-2">{{ __('Waitlist') }}</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="social_pension" value="1" {{ old('social_pension') ? 'checked' : '' }} class="rounded dark:bg-gray-700 dark:border-gray-600" />
                                    <span class="ml-2">{{ __('Social Pension') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Remarks -->
                        <div class="mb-6">
                            <x-input-label for="remarks" :value="__('Remarks')" />
                            <select id="remarks" name="remarks" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm">
                                <option value="">{{ __('Select Remarks') }}</option>
                                @foreach(\App\Constants\Remarks::list() as $remark)
                                    <option value="{{ $remark }}" {{ old('remarks') === $remark ? 'selected' : '' }}>
                                        {{ $remark }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('remarks')" class="mt-2" />
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('senior-citizens.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>{{ __('Add Senior Citizen') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
