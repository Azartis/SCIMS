<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.88) 100%), url('{{ asset('images/logo.png') }}'); background-attachment: fixed; background-size: auto 500px; background-position: center; background-repeat: no-repeat; background-color: #f3f4f6;">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 dark:bg-gray-900">
            <!-- Logo and Brand -->
            <div class="mb-8 flex flex-col items-center">
                <div class="mb-4 cursor-pointer hover:scale-105 transition-transform" onclick="openAdminLoginModal()">
                    <img src="{{ asset('images/logo.png') }}" alt="SCIMS Logo" class="w-28 h-28 object-contain">
                </div>
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">SCIMS</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Senior Citizens Management System</p>
                </div>
            </div>

            <!-- Form Container -->
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white dark:bg-gray-800 shadow-lg overflow-hidden sm:rounded-xl border border-gray-200 dark:border-gray-700 backdrop-blur-sm bg-white/95 dark:bg-gray-800/95">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                <p>&copy; {{ date('Y') }} SCIMS - Senior Citizens Management System</p>
            </div>
        </div>

        <!-- Admin Login Modal -->
        <div id="adminLoginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-lg w-full mx-4 border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Admin Login</h3>
                        <button onclick="closeAdminLoginModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Enter your admin credentials</p>
                </div>
                <div class="p-6 space-y-4">
                    <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                        @csrf
                        
                        <!-- Email Address -->
                        <div class="group">
                            <label for="admin_email" class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block">Email Address</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <input id="admin_email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                       class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                       placeholder="admin@scims.local">
                            </div>
                            @error('email')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Password -->
                        <div class="group">
                            <label for="admin_password" class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 block">Password</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400 dark:text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <input id="admin_password" type="password" name="password" required
                                       class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                       placeholder="••••••••">
                            </div>
                            @error('password')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Remember Me -->
                        <label class="inline-flex items-center gap-1.5 cursor-pointer group py-1">
                            <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-blue-600 focus:ring-2 focus:ring-blue-500 transition">
                            <span class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-300 transition">Remember me</span>
                        </label>
                        
                        <!-- Sign In Button -->
                        <button type="submit" class="w-full mt-5 px-4 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 dark:from-yellow-300 dark:to-yellow-400 text-gray-900 dark:text-gray-900 font-bold rounded-xl text-sm transition duration-200 shadow-lg hover:shadow-2xl transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 border-2 border-yellow-600 dark:border-yellow-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Sign In
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function openAdminLoginModal() {
                document.getElementById('adminLoginModal').classList.remove('hidden');
                document.getElementById('adminLoginModal').classList.add('flex');
            }

            function closeAdminLoginModal() {
                document.getElementById('adminLoginModal').classList.add('hidden');
                document.getElementById('adminLoginModal').classList.remove('flex');
            }

            // Close modal when clicking outside
            document.getElementById('adminLoginModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    closeAdminLoginModal();
                }
            });

            // Close modal on Escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !document.getElementById('adminLoginModal').classList.contains('hidden')) {
                    closeAdminLoginModal();
                }
            });
        </script>
    </body>
</html>
