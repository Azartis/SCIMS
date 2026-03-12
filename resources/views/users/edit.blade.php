<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-6">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <x-input-label for="password" :value="__('Password (Leave blank to keep current)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-6">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Role -->
                        <div class="mb-6">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm" required>
                                <option value="">{{ __('Select Role') }}</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>{{ __('Staff') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm" required>
                                <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                <option value="blocked" {{ old('status', $user->status) === 'blocked' ? 'selected' : '' }}>{{ __('Blocked') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-900 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none transition ease-in-out duration-150">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>{{ __('Update User') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
