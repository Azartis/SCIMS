{{-- Sidebar Navigation (Modern Premium Design) --}}

<aside class="w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col hidden md:flex sticky top-0 h-screen overflow-y-auto">
    <!-- Logo / Branding -->
    <div class="px-6 py-7 border-b border-slate-200 dark:border-slate-800">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
            <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 flex items-center justify-center shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
                <span class="text-white font-bold text-lg">SC</span>
            </div>
            <div class="flex-1">
                <h2 class="font-bold text-slate-900 dark:text-white text-base">OSCAS</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Seniors System</p>
            </div>
        </a>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <a 
            href="{{ route('dashboard') }}" 
            @class([
                'flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-300 group',
                'bg-gradient-to-r from-blue-50 to-blue-50/50 dark:from-blue-900/30 dark:to-blue-900/10 text-blue-700 dark:text-blue-400 shadow-sm border border-blue-200 dark:border-blue-800' => request()->routeIs('dashboard'),
                'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200' => !request()->routeIs('dashboard'),
            ])
        >
            <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h2v-6h4v6h2a1 1 0 001-1V9m0 0V5a1 1 0 011-1h2a1 1 0 011 1v4m0 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Section: Management -->
        <div class="pt-2 pb-3 mt-2">
            <p class="px-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Management</p>
        </div>

        <!-- Senior Citizens -->
        <a 
            href="{{ route('senior-citizens.index') }}" 
            @class([
                'flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-300 group',
                'bg-gradient-to-r from-green-50 to-green-50/50 dark:from-green-900/30 dark:to-green-900/10 text-green-700 dark:text-green-400 shadow-sm border border-green-200 dark:border-green-800' => request()->routeIs('senior-citizens.*'),
                'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200' => !request()->routeIs('senior-citizens.*'),
            ])
        >
            <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M12 14H8m4 0h4m-8 4h12a2 2 0 002-2v-3a6 6 0 00-6-6H6a6 6 0 00-6 6v3a2 2 0 002 2z" />
            </svg>
            <span>Senior Citizens</span>
        </a>

        <!-- SPISC -->
        <a 
            href="{{ route('spisc.index') }}" 
            @class([
                'flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-300 group',
                'bg-gradient-to-r from-amber-50 to-amber-50/50 dark:from-amber-900/30 dark:to-amber-900/10 text-amber-700 dark:text-amber-400 shadow-sm border border-amber-200 dark:border-amber-800' => request()->routeIs('spisc.*'),
                'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200' => !request()->routeIs('spisc.*'),
            ])
        >
            <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Social Pension</span>
        </a>

        <!-- Section: Analysis -->
        <div class="pt-2 pb-3 mt-2">
            <p class="px-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Analysis</p>
        </div>

        <!-- Reports -->
        <a 
            href="{{ route('reports.index') }}" 
            @class([
                'flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-300 group',
                'bg-gradient-to-r from-purple-50 to-purple-50/50 dark:from-purple-900/30 dark:to-purple-900/10 text-purple-700 dark:text-purple-400 shadow-sm border border-purple-200 dark:border-purple-800' => request()->routeIs('reports.*'),
                'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200' => !request()->routeIs('reports.*'),
            ])
        >
            <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span>Reports</span>
        </a>

        <!-- History -->
        <a 
            href="{{ route('history') }}" 
            @class([
                'flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-300 group',
                'bg-gradient-to-r from-orange-50 to-orange-50/50 dark:from-orange-900/30 dark:to-orange-900/10 text-orange-700 dark:text-orange-400 shadow-sm border border-orange-200 dark:border-orange-800' => request()->routeIs('history'),
                'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200' => !request()->routeIs('history'),
            ])
        >
            <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Change History</span>
        </a>

        <!-- Admin Section (if user is admin) -->
        @if(auth()->user()?->role === 'admin')
            <div class="pt-2 pb-3 mt-2">
                <p class="px-4 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Administration</p>
            </div>

            <!-- Admin Dashboard -->
  

            <!-- Users -->
            <a 
                href="{{ route('users.index') }}" 
                @class([
                    'flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-300 group',
                    'bg-gradient-to-r from-red-50 to-red-50/50 dark:from-red-900/30 dark:to-red-900/10 text-red-700 dark:text-red-400 shadow-sm border border-red-200 dark:border-red-800' => request()->routeIs('users.*'),
                    'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200' => !request()->routeIs('users.*'),
                ])
            >
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M12 14H8m4 0h4m-8 4h12a2 2 0 002-2v-3a6 6 0 00-6-6H6a6 6 0 00-6 6v3a2 2 0 002 2z" />
                </svg>
                <span>User Management</span>
            </a>

            <!-- Audit Logs -->
            <a 
                href="{{ route('audit-logs.index') }}" 
                @class([
                    'flex items-center px-4 py-3 rounded-xl text-sm font-semibold transition-all duration-300 group',
                    'bg-gradient-to-r from-indigo-50 to-indigo-50/50 dark:from-indigo-900/30 dark:to-indigo-900/10 text-indigo-700 dark:text-indigo-400 shadow-sm border border-indigo-200 dark:border-indigo-800' => request()->routeIs('audit-logs.*'),
                    'text-slate-700 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-slate-200' => !request()->routeIs('audit-logs.*'),
                ])
            >
                <svg class="w-5 h-5 mr-3 transition-transform group-hover:scale-110 duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Audit Logs</span>
            </a>
        @endif
    </nav>

    <!-- Footer / User Info -->
    <div class="px-6 py-5 border-t border-slate-200 dark:border-slate-800 bg-gradient-to-r from-slate-50 to-slate-50/50 dark:from-slate-800/50 dark:to-slate-800/30">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 flex items-center justify-center shadow-md flex-shrink-0">
                <span class="text-white font-bold text-sm">{{ auth()->user()->name[0] }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-900 dark:text-slate-50 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 truncate font-medium">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</aside>
