<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">Admin Dashboard</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">System overview & management</p>
            </div>
            <a href="{{ route('admin.users') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                User Management
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Admin System Metrics --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Total Users --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm hover:shadow-lg transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total Users</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $adminMetrics['totalUsers'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                <span class="text-blue-600 dark:text-blue-400">{{ $adminMetrics['adminCount'] }}</span> Admin · 
                                <span class="text-green-600 dark:text-green-400">{{ $adminMetrics['staffCount'] }}</span> Staff
                            </p>
                        </div>
                        <div class="p-2.5 bg-slate-100 dark:bg-slate-700/50 rounded-lg">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M12 14H8m4 0h4m-8 4h12a2 2 0 002-2v-3a6 6 0 00-6-6H6a6 6 0 00-6 6v3a2 2 0 002 2z" /></svg>
                        </div>
                    </div>
                </div>

                {{-- Total Senior Records --}}
                <a href="{{ route('senior-citizens.index') }}" class="group bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm hover:shadow-lg hover:border-blue-400 dark:hover:border-blue-600 transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total Records</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $adminMetrics['totalRecords'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Senior citizens</p>
                        </div>
                        <div class="p-2.5 bg-blue-100 dark:bg-blue-900/30 rounded-lg group-hover:bg-blue-200 dark:group-hover:bg-blue-900 transition-colors">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 016-6v-2a6 6 0 00-6 6v2z" /></svg>
                        </div>
                    </div>
                </a>

                {{-- Audit Logs --}}
                <a href="{{ route('audit-logs.index') }}" class="group bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm hover:shadow-lg hover:border-purple-400 dark:hover:border-purple-600 transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">Audit Logs</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ $adminMetrics['auditLogsCount'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">System changes</p>
                        </div>
                        <div class="p-2.5 bg-purple-100 dark:bg-purple-900/30 rounded-lg group-hover:bg-purple-200 dark:group-hover:bg-purple-900 transition-colors">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                    </div>
                </a>

                {{-- System Status --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide">System Status</p>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400 mt-1 flex items-center gap-1.5">
                                <span class="w-2 h-2 bg-green-600 dark:bg-green-400 rounded-full"></span>
                                Healthy
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">All systems operational</p>
                        </div>
                        <div class="p-2.5 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Key metrics from main dashboard (seniors & pension) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="{{ route('senior-citizens.index') }}" class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-md hover:shadow-lg hover:border-blue-400 dark:hover:border-blue-600 transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total Seniors</p>
                            <p class="text-xl font-bold text-slate-900 dark:text-white mt-1 leading-tight">{{ $dashboardData['metrics']['totalSeniors'] }}</p>
                            @if(($dashboardData['metrics']['totalTrend'] ?? 0) !== 0)
                                <p class="text-xs mt-1 {{ $dashboardData['metrics']['totalTrend'] > 0 ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }}">
                                    {{ $dashboardData['metrics']['totalTrend'] > 0 ? '+' : '' }}{{ $dashboardData['metrics']['totalTrend'] }}% vs last month
                                </p>
                            @endif
                        </div>
                        <div class="p-3 bg-slate-100 dark:bg-slate-700/50 rounded-lg group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                            <svg class="w-6 h-6 text-slate-600 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 016-6v-2a6 6 0 00-6 6v2z" /></svg>
                        </div>
                    </div>
                </a>

                <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-md hover:shadow-lg hover:border-blue-400 dark:hover:border-blue-600 transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Pension Recipients</p>
                            <p class="text-xl font-bold text-slate-900 dark:text-white mt-1 leading-tight">{{ $dashboardData['metrics']['pensionRecipients'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $dashboardData['metrics']['pensionCoverage'] }}% coverage</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30 transition-colors">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-md hover:shadow-lg hover:border-amber-400 dark:hover:border-amber-600 transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Waitlist</p>
                            <p class="text-xl font-bold text-slate-900 dark:text-white mt-1 leading-tight">{{ $dashboardData['metrics']['onWaitlist'] }}</p>
                        </div>
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-lg group-hover:bg-amber-100 dark:group-hover:bg-amber-900/30 transition-colors">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-md hover:shadow-lg hover:border-purple-400 dark:hover:border-purple-600 transition-all duration-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">With Disability</p>
                            <p class="text-xl font-bold text-slate-900 dark:text-white mt-1 leading-tight">{{ $dashboardData['metrics']['withDisability'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $dashboardData['metrics']['disabilityRate'] }}% of total</p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg group-hover:bg-purple-100 dark:group-hover:bg-purple-900/30 transition-colors">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
                    <h3 class="text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wide mb-4">Demographics at a glance</h3>
                    <div class="h-48">
                        <canvas id="sexChart"></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
                    <h3 class="text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wide mb-4">Age distribution</h3>
                    <div class="h-48">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Age milestones --}}
            @php $ageStats = $dashboardData['ageGroupStats'] ?? []; @endphp
            @if(!empty($ageStats))
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-8 shadow-md lg:col-span-2">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <span class="text-base font-semibold text-slate-700 dark:text-slate-300">Octagenarian:</span>
                        <div class="flex flex-wrap items-center gap-3">
                            @foreach($ageStats as $ageGroup)
                                <a href="{{ route('age-milestones.index', ['age' => $ageGroup['age']]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-700/50 hover:bg-blue-100 dark:hover:bg-blue-900/30 text-base font-medium text-slate-700 dark:text-slate-300 hover:text-blue-700 dark:hover:text-blue-300 transition-colors whitespace-nowrap">
                                    {{ $ageGroup['age'] }}y: <span class="font-bold">{{ $ageGroup['count'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Recent Activities Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Recent Users --}}
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wide">Recent Users</h3>
                        <a href="{{ route('admin.users') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View all</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($adminMetrics['recentUsers'] as $user)
                            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-700 last:border-0">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $user->role === 'admin' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 dark:text-slate-400">No users yet</p>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Audit Logs --}}
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wide">Recent Changes</h3>
                        <a href="{{ route('audit-logs.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View all</a>
                    </div>
                    <div class="space-y-2 text-xs">
                        @forelse($adminMetrics['recentChanges'] as $log)
                            <div class="flex items-start gap-3 pb-2 border-b border-slate-200 dark:border-slate-700 last:border-0">
                                <div class="flex-1">
                                    <p class="font-medium text-slate-900 dark:text-white">
                                        <span class="text-slate-600 dark:text-slate-400">{{ $log->user?->name ?? 'System' }}</span>
                                        <span class="text-slate-500 dark:text-slate-400">{{ $log->action }}</span>
                                        <span class="text-slate-600 dark:text-slate-400">{{ class_basename($log->auditable_type) }}</span>
                                    </p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-slate-500 dark:text-slate-400">No changes recorded</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Admin Quick Access --}}
            <div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wide mb-4">⚙️ Admin Actions</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <a href="{{ route('admin.users') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-red-400 dark:hover:border-red-600 hover:bg-red-50/50 dark:hover:bg-red-900/20 transition-all duration-200 group">
                        <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-red-200/60 dark:group-hover:bg-red-800/40 transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-red-600 dark:group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646M12 14H8m4 0h4m-8 4h12a2 2 0 002-2v-3a6 6 0 00-6-6H6a6 6 0 00-6 6v3a2 2 0 002 2z" /></svg>
                        </div>
                        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">Users</span>
                    </a>

                    <a href="{{ route('users.create') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-blue-400 dark:hover:border-blue-600 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                        <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-blue-200/60 dark:group-hover:bg-blue-800/40 transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        </div>
                        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">Add User</span>
                    </a>

                    <a href="{{ route('audit-logs.index') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-purple-400 dark:hover:border-purple-600 hover:bg-purple-50/50 dark:hover:bg-purple-900/20 transition-all duration-200 group">
                        <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-purple-200/60 dark:group-hover:bg-purple-800/40 transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-purple-600 dark:group-hover:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">Audit Logs</span>
                    </a>

                    <a href="{{ route('senior-citizens.index') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-blue-400 dark:hover:border-blue-600 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                        <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-blue-200/60 dark:group-hover:bg-blue-800/40 transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0zM6 20a6 6 0 016-6v-2a6 6 0 00-6 6v2z" /></svg>
                        </div>
                        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">Browse Seniors</span>
                    </a>

                    <a href="{{ route('reports.index') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-green-400 dark:hover:border-green-600 hover:bg-green-50/50 dark:hover:bg-green-900/20 transition-all duration-200 group">
                        <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-green-200/60 dark:group-hover:bg-green-800/40 transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-green-600 dark:group-hover:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        </div>
                        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">Reports</span>
                    </a>

                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-orange-400 dark:hover:border-orange-600 hover:bg-orange-50/50 dark:hover:bg-orange-900/20 transition-all duration-200 group">
                        <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-orange-200/60 dark:group-hover:bg-orange-800/40 transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-orange-600 dark:group-hover:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12a9 9 0 009-9 9.75 9.75 0 016.74 2.74L21 8M3 12a9 9 0 009 9 9.75 9.75 0 006.74-2.74L21 16M3 12h18m-9-9v18" /></svg>
                        </div>
                        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">Back</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        @php
            $sexData = $dashboardData['distributions']['genderChart'] ?? ['labels' => [], 'data' => [], 'colors' => []];
            $sexColors = $sexData['colors'] ?? ['#3b82f6', '#ec4899'];
        @endphp
        const sexCtx = document.getElementById('sexChart')?.getContext('2d');
        if (sexCtx) {
            new Chart(sexCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($sexData['labels'] ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($sexData['data'] ?? []) !!},
                        backgroundColor: {!! json_encode($sexColors) !!},
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.1)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        @php
            $ageData = $dashboardData['distributions']['ageChart'] ?? ['labels' => [], 'data' => [], 'colors' => []];
            $ageColors = $ageData['colors'] ?? ['#3b82f6', '#8b5cf6', '#ec4899', '#f97316', '#eab308'];
        @endphp
        const ageCtx = document.getElementById('ageChart')?.getContext('2d');
        if (ageCtx) {
            new Chart(ageCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($ageData['labels'] ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($ageData['data'] ?? []) !!},
                        backgroundColor: {!! json_encode($ageColors) !!},
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.1)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    </script>
@endpush
