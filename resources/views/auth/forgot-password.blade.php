<x-guest-layout>
    <div class="space-y-4">
        <!-- Header -->
        <div class="text-center mb-2">
            <h2 class="text-3xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 dark:from-amber-400 dark:to-orange-400 bg-clip-text text-transparent">Reset Password</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-2 text-sm">We'll send you a link to reset your password</p>
        </div>

        <!-- Info Message -->
        <div class="p-3 rounded-lg bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 border border-blue-200 dark:border-blue-700 flex items-start gap-2">
            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z" clip-rule="evenodd" />
            </svg>
            <p class="text-xs text-blue-800 dark:text-blue-200">
                {{ __('Entered your email address and we\'ll send you a password reset link.') }}
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="p-3 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border border-green-200 dark:border-green-700 flex items-start gap-2">
                <svg class="w-4 h-4 text-green-600 dark:text-green-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-xs font-medium text-green-800 dark:text-green-200">{{ session('status') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-3">
            @csrf

            <!-- Email Address -->
            <div class="group">
                <x-input-label for="email" :value="__('Email Address')" class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block" />
                <div class="relative">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <x-text-input 
                        id="email" 
                        class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition duration-200" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus
                        placeholder="admin@oscas.local"
                    />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 dark:text-red-400 text-sm" />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full mt-3 px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 dark:from-amber-500 dark:to-orange-500 dark:hover:from-amber-600 dark:hover:to-orange-600 text-white font-semibold rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                {{ __('Send Password Reset Link') }}
            </button>

            <!-- Back to Login -->
            <div class="text-center pt-1 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    {{ __('Remember your password?') }}
                    <a href="{{ route('login') }}" class="text-amber-600 dark:text-amber-400 hover:text-amber-700 dark:hover:text-amber-300 font-semibold transition">
                        {{ __('Sign in') }}
                    </a>
                </p>
            </div>
        </form>
    </div>
</x-guest-layout>
