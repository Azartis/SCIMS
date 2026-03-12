<x-guest-layout>
    <div class="space-y-4">
        <!-- Header -->
        <div class="text-center mb-2">
            <h2 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 dark:from-purple-400 dark:to-pink-400 bg-clip-text text-transparent">Create New Password</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">Enter your new password below</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-3">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="group">
                <x-input-label for="email" :value="__('Email Address')" class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block" />
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <x-text-input 
                        id="email" 
                        class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                        type="email" 
                        name="email" 
                        :value="old('email', $request->email)" 
                        required 
                        autofocus 
                        autocomplete="username"
                        placeholder="admin@scims.local"
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 dark:text-red-400 text-sm" />
            </div>

            <!-- Password -->
            <div class="group">
                <x-input-label for="password" :value="__('New Password')" class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block" />
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <x-text-input 
                        id="password" 
                        class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                </div>
                <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">At least 8 characters recommended</p>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 dark:text-red-400 text-sm" />
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
                        class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition duration-200"
                        type="password"
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 dark:text-red-400 text-sm" />
            </div>

            <!-- Reset Button -->
            <button type="submit" class="w-full mt-3 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 dark:from-purple-500 dark:to-pink-500 dark:hover:from-purple-600 dark:hover:to-pink-600 text-white font-semibold rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                {{ __('Reset Password') }}
            </button>
        </form>
    </div>
</x-guest-layout>
