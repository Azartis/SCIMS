<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Validation errors alert -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 rounded">
                            <h3 class="font-semibold mb-2">{{ __('Please fix the following errors:') }}</h3>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full @error('name') is-invalid @enderror" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full @error('email') is-invalid @enderror" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" class="block mt-1 w-full @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" required />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm @error('role') is-invalid @enderror" required>
                                <option value="">{{ __('Select Role') }}</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>{{ __('Staff') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status','active') === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                <option value="blocked" {{ old('status') === 'blocked' ? 'selected' : '' }}>{{ __('Blocked') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-2">
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-transparent rounded-md font-medium text-sm text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>{{ __('Add User') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
