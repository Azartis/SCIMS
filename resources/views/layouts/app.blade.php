<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SCIMS') }}</title>

        <!-- Fonts: Inter (SaaS standard) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-50 overflow-x-hidden">
        <div class="min-h-screen flex flex-col md:flex-row">
            <!-- Sidebar Navigation (Hidden on Mobile) -->
            @include('layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col w-full">
                <!-- Top Navigation -->
                @include('layouts.top-nav')

                <!-- Page Header (if provided) -->
                @isset($header)
                    <header class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 w-full">
                        <div class="w-full mx-auto px-2 sm:px-3 md:px-6 lg:px-8 py-3 sm:py-4 lg:py-6">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Main Content Area -->
                <main class="flex-1 overflow-auto w-full">
                    <div class="w-full mx-auto px-2 sm:px-3 md:px-6 lg:px-8 py-3 sm:py-6 md:py-8">
                        @isset($slot)
                            {{ $slot }}
                        @else
                            @yield('content')
                        @endisset
                    </div>
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
