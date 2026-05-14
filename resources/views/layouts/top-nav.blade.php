{{-- Top Navigation Bar (iOS/macOS Design) --}}

<header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 w-full overflow-visible shadow-sm" x-data="{ mobileMenuOpen: false }">
    <div class="w-full px-2 sm:px-4 lg:px-8 h-14 sm:h-16 flex items-center justify-between min-h-14 sm:min-h-16 gap-2">
        <!-- Left: Mobile Menu + Title -->
        <div class="flex items-center gap-4 min-w-0">
            <!-- Mobile Menu Button (Always Visible on Mobile) -->
            <button 
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200 flex-shrink-0 relative z-40"
                :title="mobileMenuOpen ? 'Close menu' : 'Open menu'"
            >
                <svg class="w-6 h-6" :class="{ 'block': !mobileMenuOpen, 'hidden': mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <line x1="4" y1="6" x2="20" y2="6"></line>
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="18" x2="20" y2="18"></line>
                </svg>
                <svg class="w-6 h-6" :class="{ 'hidden': !mobileMenuOpen, 'block': mobileMenuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
            </button>

            <!-- Page Title (Desktop) -->
            <div class="hidden md:block min-w-0">
                <h1 class="text-sm lg:text-base font-semibold text-slate-900 dark:text-slate-50 truncate">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>
        </div>

        <!-- Right: Dark Mode + User Menu -->
        <div class="flex items-center gap-2 sm:gap-3 lg:gap-4 flex-shrink-0">

            <!-- Dark Mode Toggle -->
            <button 
                x-data="{ toggle() { this.$dispatch('dark-mode-toggle'); } }"
                @click="toggle()"
                class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200 flex-shrink-0"
                title="Toggle dark mode"
            >
                <svg class="w-5 sm:w-5 h-5 sm:h-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                </svg>
                <svg class="w-5 sm:w-5 h-5 sm:h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                    <path d="M19.707 5.293a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zm2 2a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.293 5.293a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414zM5.293 14.707a1 1 0 011.414 0l.707.707a1 1 0 01-1.414 1.414l-.707-.707a1 1 0 010-1.414z" />
                </svg>
            </button>

            <!-- User Menu Dropdown -->
            <div class="relative flex-shrink-0" x-data="{ open: false }">
                <button 
                    @click="open = !open" 
                    @click.away="open = false"
                    class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200"
                >
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm flex-shrink-0 shadow-sm">
                        {{ auth()->user()->name[0] }}
                    </div>
                </button>

                <!-- User Dropdown Menu -->
                <div 
                    x-show="open" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 translate-y-1"
                    x-transition:enter-end="transform opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="transform opacity-100 translate-y-0"
                    x-transition:leave-end="transform opacity-0 translate-y-1"
                    class="absolute right-0 mt-2 w-48 rounded-2xl shadow-xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 z-[9999] overflow-hidden"
                >
                    <!-- User Info -->
                    <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-slate-50 to-slate-50/50 dark:from-slate-800/50 dark:to-slate-800/30">
                        <p class="font-medium text-slate-900 dark:text-slate-50 text-sm truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50 transition-colors duration-200">
                            Profile Settings
                        </a>
                    </div>

                    <!-- Logout -->
                    <div class="border-t border-slate-200 dark:border-slate-700 py-2">
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200 font-medium">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <nav 
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-y-0 origin-top"
        x-transition:enter-end="transform opacity-100 scale-y-100 origin-top"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="transform opacity-100 scale-y-100 origin-top"
        x-transition:leave-end="transform opacity-0 scale-y-0 origin-top"
        class="md:hidden fixed top-14 sm:top-16 left-0 right-0 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-2 sm:px-4 py-4 space-y-2 overflow-x-hidden z-30 max-h-[calc(100vh-3.5rem)] overflow-y-auto shadow-md"
    >
        <!-- Mobile Navigation Links -->
        @auth
            <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors truncate">
                📊 Dashboard
            </a>
            <a href="{{ route('senior-citizens.index') }}" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors truncate">
                👴 Senior Citizens
            </a>
            <a href="{{ route('reports.index') }}" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors truncate">
                📈 Reports
            </a>
            <a href="{{ route('spisc.index') }}" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors truncate">
                💰 Social Pension
            </a>
            @can('isAdmin', auth()->user())
                <hr class="my-3 border-slate-300 dark:border-slate-600">
                @can('viewUsers', App\Models\User::class)
                    <a href="{{ route('users.index') }}" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors truncate">
                        👥 User Management
                    </a>
                @endcan
                @can('viewAuditLogs', auth()->user())
                    <a href="{{ route('audit-logs.index') }}" @click="mobileMenuOpen = false" class="block px-4 py-3 rounded-lg text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors truncate">
                        📋 Audit Logs
                    </a>
                @endcan
            @endcan
        @endauth
    </nav>
</header>

@push('scripts')
<script>
    // Dark mode toggle
    document.addEventListener('dark-mode-toggle', function() {
        const isDark = document.documentElement.classList.contains('dark');
        if (isDark) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('darkMode', 'false');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('darkMode', 'true');
        }
    });
</script>
@endpush
