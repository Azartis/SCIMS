<x-guest-layout>
    <div class="space-y-4">
        <!-- Header -->
        <div class="text-center mb-2">
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-red-100 to-red-50 dark:from-red-900/30 dark:to-red-800/30 flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold bg-gradient-to-r from-red-600 to-red-700 dark:from-red-400 dark:to-red-300 bg-clip-text text-transparent">Confirm Password</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">This is a secure area. Please confirm your password to continue</p>
        </div>

        <!-- Security Message -->
        <div class="p-3 rounded-lg bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/30 dark:to-orange-900/30 border border-red-200 dark:border-red-700 flex items-start gap-2">
            <svg class="w-4 h-4 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1a1 1 0 001 1h6a1 1 0 001-1v-1zm-1-4a1 1 0 11-2 0 1 1 0 012 0zM20 20H4v-1a6 6 0 0116 0v1z" clip-rule="evenodd" />
            </svg>
            <p class="text-xs text-red-800 dark:text-red-200">
                {{ __('This is a secure area of the application. Please confirm your password to continue accessing this section.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-3">
            @csrf

            <!-- Password -->
            <div class="group">
                <x-input-label for="password" :value="__('Password')" class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block" />
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <x-text-input 
                        id="password" 
                        class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition duration-200"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 dark:text-red-400 text-sm" />
            </div>

            <!-- Confirm Button -->
            <button type="submit" class="w-full mt-3 px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 dark:from-red-500 dark:to-red-600 dark:hover:from-red-600 dark:hover:to-red-700 text-white font-semibold rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                {{ __('Confirm Password') }}
            </button>
        </form>
    </div>
</x-guest-layout>
