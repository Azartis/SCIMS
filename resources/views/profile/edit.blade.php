<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <!-- Active Sessions -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Browser Sessions') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Manage and log out your active sessions on other browsers and devices.') }}
                    </p>

                    @if (session('status') === 'other-sessions-logged-out')
                        <div class="mt-4 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Logged out of other browser sessions.') }}
                        </div>
                    @endif

                    <div class="mt-6 space-y-6">
                        @foreach ($sessions ?? collect() as $session)
                            <div class="flex items-center">
                                <div class="ml-3">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        @if (str_contains($session->user_agent, 'Windows'))
                                            {{ __('Windows') }}
                                        @elseif (str_contains($session->user_agent, 'Macintosh'))
                                            {{ __('Mac') }}
                                        @elseif (str_contains($session->user_agent, 'Android'))
                                            {{ __('Android') }}
                                        @elseif (str_contains($session->user_agent, 'iPhone'))
                                            {{ __('iPhone') }}
                                        @else
                                            {{ __('Unknown') }}
                                        @endif
                                        &middot; {{ $session->ip_address }}
                                    </div>
                                    <div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('Last active') }}: {{ $session->last_active->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($sessions && $sessions->count() > 1)
                        <div class="flex items-center mt-6">
                            <form method="POST" action="{{ route('profile.other-sessions.destroy') }}">
                                @csrf
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="current-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />

                                <x-primary-button class="mt-3">
                                    {{ __('Log Out Other Sessions') }}
                                </x-primary-button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
