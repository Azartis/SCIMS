<x-guest-layout>
    <div class="space-y-3">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-green-600 to-green-700 dark:from-green-400 dark:to-green-300 bg-clip-text text-transparent">Create Account</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1 text-xs">Join SCIMS to manage senior citizens</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-2.5">
            @csrf

            <!-- Name -->
            <div class="group">
                <x-input-label for="name" :value="__('Full Name')" class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block" />
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <x-text-input 
                        id="name" 
                        class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" 
                        type="text" 
                        name="name" 
                        :value="old('name')" 
                        required 
                        autofocus 
                        placeholder="John Doe"
                        autocomplete="name" 
                    />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-600 dark:text-red-400 text-xs" />
            </div>

            <!-- Email Address -->
            <div class="group">
                <x-input-label for="email" :value="__('Email Address')" class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block" />
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <x-text-input 
                        id="email" 
                        class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        placeholder="you@example.com"
                        autocomplete="email" 
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-600 dark:text-red-400 text-xs" />
            </div>

            <!-- Password -->
            <div class="group">
                <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block" />
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <x-text-input 
                        id="password" 
                        class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                        type="password"
                        name="password"
                        required 
                        placeholder="••••••••"
                        autocomplete="new-password" 
                    />
                </div>
                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Min 8 chars</p>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-600 dark:text-red-400 text-xs" />
            </div>

            <!-- Confirm Password -->
            <div class="group">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block" />
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <x-text-input 
                        id="password_confirmation" 
                        class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200"
                        type="password"
                        name="password_confirmation" 
                        required 
                        placeholder="••••••••"
                        autocomplete="new-password" 
                    />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-600 dark:text-red-400 text-xs" />
            </div>

            <!-- Agreement Notice -->
            <div class="p-2 bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 border border-blue-200 dark:border-blue-700 rounded-xl flex items-start gap-2">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z" clip-rule="evenodd" />
                </svg>
                <p class="text-xs text-blue-800 dark:text-blue-200 leading-tight">
                    By creating an account, you agree to our community guidelines and data protection policies.
                </p>
            </div>

            <!-- Register Button -->
            <button type="submit" class="w-auto mx-auto block mt-3 px-12 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 dark:from-yellow-300 dark:to-yellow-400 text-gray-900 dark:text-gray-900 font-bold rounded-xl text-base transition duration-200 shadow-lg hover:shadow-2xl transform hover:scale-[1.05] active:scale-[0.98] flex items-center justify-center gap-2 border-2 border-yellow-600 dark:border-yellow-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM9 19v-2a6 6 0 0112 0v2a2 2 0 01-2 2H7a2 2 0 01-2-2z" />
                </svg>
                {{ __('Create Account') }}
            </button>

            <!-- Login Link -->
            <div class="text-center pt-1 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    {{ __('Already have an account?') }}
                    <a href="{{ route('login') }}" class="text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 font-semibold transition">
                        {{ __('Sign in here') }}
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
