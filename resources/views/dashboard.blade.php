<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">Dashboard</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    @if(auth()->user()->role === 'staff')
                        Overview (Limited Access)
                    @else
                        Overview & quick access
                    @endif
                </p>
            </div>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                View reports
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Staff Access Notice --}}
            @if(auth()->user()->role === 'staff')
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 flex items-start gap-3">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    <div>
                        <p class="text-sm font-medium text-amber-900 dark:text-amber-200">Limited Access</p>
                        <p class="text-sm text-amber-800 dark:text-amber-300 mt-0.5">This view is for staff users only: you can browse data and run reports, but <strong>user management and other admin controls are disabled</strong>.</p>
                    </div>
                </div>
            @endif

            {{-- Key metrics --}}
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
                    @php $genderData = $dashboardData['distributions']['genderChart'] ?? []; @endphp
                    @if(!empty($genderData['labels']))
                        <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                            @foreach($genderData['labels'] as $index => $label)
                                <div class="flex items-center gap-1.5 px-2 py-1 bg-slate-50 dark:bg-slate-700/50 rounded text-xs">
                                    <div class="w-2 h-2 rounded-full" style="background-color: {{ $genderData['colors'][$index] ?? '#3b82f6' }}"></div>
                                    <span class="font-medium text-slate-700 dark:text-slate-300">{{ $label }}: {{ $genderData['data'][$index] ?? 0 }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
                    <h3 class="text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wide mb-4">Age distribution</h3>
                    <div class="h-48">
                        <canvas id="ageChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Quick access --}}
            <div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wide mb-4">🚀 Quick access</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    <a href="{{ route('senior-citizens.index') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-blue-400 dark:hover:border-blue-600 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                        <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-blue-200/60 dark:group-hover:bg-blue-800/40 transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 6a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">Browse Seniors</span>
                    </a>
                    @if(auth()->user()->role !== 'staff')
                        <a href="{{ route('senior-citizens.create') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-blue-400 dark:hover:border-blue-600 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all duration-200 group">
                            <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-blue-200/60 dark:group-hover:bg-blue-800/40 transition-colors">
                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </div>
                            <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">Add New</span>
                        </a>
                    @endif
                    <a href="{{ route('reports.index') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-purple-400 dark:hover:border-purple-600 hover:bg-purple-50/50 dark:hover:bg-purple-900/20 transition-all duration-200 group">
                        <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-purple-200/60 dark:group-hover:bg-purple-800/40 transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-purple-600 dark:group-hover:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        </div>
                        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">Reports</span>
                    </a>
                    <a href="{{ route('spisc.index') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-amber-400 dark:hover:border-amber-600 hover:bg-amber-50/50 dark:hover:bg-amber-900/20 transition-all duration-200 group">
                        <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-amber-200/60 dark:group-hover:bg-amber-800/40 transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-amber-600 dark:group-hover:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">Pensions</span>
                    </a>
                    <a href="{{ route('history') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-orange-400 dark:hover:border-orange-600 hover:bg-orange-50/50 dark:hover:bg-orange-900/20 transition-all duration-200 group">
                        <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-orange-200/60 dark:group-hover:bg-orange-800/40 transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-orange-600 dark:group-hover:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">History</span>
                    </a>
                    @if(auth()->user()?->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-4 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md hover:border-red-400 dark:hover:border-red-600 hover:bg-red-50/50 dark:hover:bg-red-900/20 transition-all duration-200 group">
                            <div class="p-2 bg-slate-200/60 dark:bg-slate-700 rounded-lg group-hover:bg-red-200/60 dark:group-hover:bg-red-800/40 transition-colors">
                                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-red-600 dark:group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            </div>
                            <span class="font-medium text-slate-800 dark:text-slate-200 text-sm">Admin Panel</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Age milestones --}}
            @php $ageStats = $dashboardData['ageGroupStats'] ?? []; @endphp
            @if(!empty($ageStats))
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Octagenarian:</span>
                        @foreach($ageStats as $ageGroup)
                            <a href="{{ route('age-milestones.index', ['age' => $ageGroup['age']]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 dark:bg-slate-700/50 hover:bg-blue-100 dark:hover:bg-blue-900/30 text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                                {{ $ageGroup['age'] }}y: <span class="font-bold">{{ $ageGroup['count'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

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

</x-app-layout>
