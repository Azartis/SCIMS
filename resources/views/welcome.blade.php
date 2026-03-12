<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>OSCAS - Online Senior Citizens Assessment System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            {{-- If assets are not built, rely on a minimal Tailwind CDN fallback for styling during quick local testing --}}
            <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen flex flex-col">
        <!-- Navigation Header -->
        <header class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] py-4 px-6 lg:px-8">
            <div class="max-w-6xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#f53003] to-[#c41e0e] dark:from-[#FF4433] dark:to-[#FF750F] rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">S</span>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg">OSCAS</h1>
                        <p class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Senior Citizens Management</p>
                    </div>
                </div>

                @if (Route::has('login'))
                    <nav class="flex items-center gap-4">
                        @auth
                            <span class="text-sm">Welcome, <strong>{{ Auth::user()->name }}</strong></span>
                            <a
                                href="{{ url('/dashboard') }}"
                                class="px-4 py-2 bg-[#1b1b18] dark:bg-white text-white dark:text-[#1b1b18] rounded-md text-sm font-medium hover:bg-black dark:hover:bg-gray-100 transition"
                            >
                                Dashboard
                            </a>
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center px-6 lg:px-8 py-12 lg:py-20">
            <div class="max-w-md w-full">
                <div class="space-y-8 text-center">
                    <div class="flex justify-center">
                        <div class="w-20 h-20 bg-gradient-to-br from-[#f53003] to-[#c41e0e] rounded-full flex items-center justify-center cursor-pointer hover:scale-105 transition-transform" onclick="openAdminLoginModal()">
                            <span class="text-white text-3xl font-bold">S</span>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Welcome to OSCAS</h2>
                        <p class="text-[#706f6c] dark:text-[#A1A09A] mt-2">Senior Citizens Information Management System</p>
                    </div>

                    <div class="mt-8">
                        <button onclick="openAdminLoginModal()" class="w-full px-6 py-3 bg-[#f53003] text-white rounded-lg hover:bg-[#c41e0e] transition-colors text-sm font-medium">
                            Admin Login
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Admin Login Modal -->
        <div id="adminLoginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white dark:bg-[#161615] rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-[#3E3E3A]">
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC]">Admin Login</h3>
                    <button onclick="closeAdminLoginModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                       class="mt-1 block w-full px-3 py-2 border border-[#19140035] dark:border-[#3E3E3A] rounded-md shadow-sm focus:outline-none focus:ring-[#f53003] focus:border-[#f53003] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Password</label>
                                <input id="password" type="password" name="password" required
                                       class="mt-1 block w-full px-3 py-2 border border-[#19140035] dark:border-[#3E3E3A] rounded-md shadow-sm focus:outline-none focus:ring-[#f53003] focus:border-[#f53003] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center justify-between">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember" class="rounded border-[#19140035] dark:border-[#3E3E3A] text-[#f53003] focus:ring-[#f53003]">
                                    <span class="ml-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">Remember me</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="w-full px-4 py-2 bg-[#f53003] text-white rounded-md hover:bg-[#c41e0e] transition-colors">
                                Sign In
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="border-t border-gray-200 dark:border-gray-700 py-6 px-6 lg:px-8 text-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} OSCAS - Online Senior Citizens Assessment System. All rights reserved.</p>
        </footer>

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
