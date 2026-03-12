<nav x-data="{ open: false, masterlistOpen: false }" class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo/Brand -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                        <span class="text-2xl">👥</span>
                        <div class="hidden sm:block">
                            <div class="text-sm font-bold text-gray-900 dark:text-gray-100">OSCAS</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Senior Citizens</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center gap-2">
                        <span>🏠</span>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Masterlist Dropdown -->
                    <div class="relative" @mouseenter="masterlistOpen = true" @mouseleave="masterlistOpen = false">
                        <button class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('senior-citizens.*', 'senior-citizens.archive') ? 'text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700' : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100' }} inline-flex items-center gap-2 transition">
                            <span>📋</span>
                            {{ __('Masterlist') }}
                            <svg class="h-4 w-4" :class="{'rotate-180': masterlistOpen}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="masterlistOpen" @click.away="masterlistOpen = false" x-transition class="absolute left-0 mt-0 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg z-50">
                            <a href="{{ route('senior-citizens.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-t-lg flex items-center gap-2 {{ request()->routeIs('senior-citizens.index') ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300' : '' }}">
                                <span>📊</span> View All
                            </a>
                            <a href="{{ route('senior-citizens.create') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 flex items-center gap-2">
                                <span>➕</span> Add New
                            </a>
                            <a href="{{ route('senior-citizens.archive') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 flex items-center gap-2">
                                <span>📦</span> Archived
                            </a>
                            <div class="border-t border-gray-200 dark:border-gray-600"></div>
                            <a href="{{ route('reports.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-b-lg flex items-center gap-2">
                                <span>📈</span> Reports
                            </a>
                        </div>
                    </div>

                    <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="flex items-center gap-2">
                        <span>📊</span>
                        {{ __('Reports') }}
                    </x-nav-link>

                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" class="flex items-center gap-2">
                            <span>⚙️</span>
                            {{ __('Users') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <span>👤</span>
                            <div class="ms-1">{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                            <span>👤</span>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2">
                                <span>🚪</span>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none transition">
                    <svg class="h-6 w-6" :class="{'hidden': open, 'inline-flex': ! open }" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" :class="{'hidden': ! open, 'inline-flex': open }" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                🏠 {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('senior-citizens.index')" :active="request()->routeIs('senior-citizens.index')">
                📋 {{ __('View All Senior Citizens') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('senior-citizens.create')" :active="request()->routeIs('senior-citizens.create')">
                ➕ {{ __('Add Senior Citizen') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('senior-citizens.archive')" :active="request()->routeIs('senior-citizens.archive')">
                📦 {{ __('Archived') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                📊 {{ __('Reports') }}
            </x-responsive-nav-link>
            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    ⚙️ {{ __('Users') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4 py-2">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    👤 {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        🚪 {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
