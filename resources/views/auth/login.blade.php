<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class=\"mb-3 p-3 rounded-lg bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 border border-green-200 dark:border-green-700 flex items-start gap-2\">
            <svg class=\"w-4 h-4 text-green-600 dark:text-green-400 mt-0.5 flex-shrink-0\" fill=\"currentColor\" viewBox=\"0 0 20 20\">
                <path fill-rule=\"evenodd\" d=\"M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z\" clip-rule=\"evenodd\" />
            </svg>
            <p class=\"text-xs font-medium text-green-800 dark:text-green-200\">{{ session('status') }}</p>
        </div>
    @endif

    <div class="space-y-4">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-400 dark:to-blue-300 bg-clip-text text-transparent">Welcome Back</h2>
            <p class="text-gray-600 dark:text-gray-400 mt-1 text-xs">Sign in to your account</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-3">
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
                        class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        placeholder="admin@oscas.local"
                        autocomplete="username" 
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
                        class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        type="password"
                        name="password"
                        required 
                        placeholder="••••••••"
                        autocomplete="current-password" 
                    />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-600 dark:text-red-400 text-xs" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between py-1">
                <label for="remember_me" class="inline-flex items-center gap-1.5 cursor-pointer group">
                    <input id="remember_me" type="checkbox" class="w-3.5 h-3.5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-blue-600 focus:ring-2 focus:ring-blue-500 transition" name="remember">
                    <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold transition" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <!-- Sign In Button -->
            <button type="submit" class="w-auto mx-auto block mt-4 px-12 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 dark:from-yellow-300 dark:to-yellow-400 text-gray-900 dark:text-gray-900 font-bold rounded-xl text-base transition duration-200 shadow-lg hover:shadow-2xl transform hover:scale-[1.05] active:scale-[0.98] flex items-center justify-center gap-2 border-2 border-yellow-600 dark:border-yellow-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                {{ __('Sign In') }}
            </button>

            <!-- Register Link Disabled - Only admins can create user accounts -->
            {{-- <div class="text-center pt-1 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold transition">
                        {{ __('Create one now') }}
                    </a>
                </p>
            </div> --}}
        </form>
    </div>
</x-guest-layout>
