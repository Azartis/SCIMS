<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">Reports & Analytics</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Detailed reports and exports</p>
            </div>
            <a href="{{ route('reports.export') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 dark:bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 dark:hover:bg-green-500 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Export CSV
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto space-y-8">
            
            <!-- Summary Stats - compact row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-blue-600 dark:text-blue-400 text-sm font-semibold uppercase tracking-wide">Total Records</p>
                            <p class="text-4xl font-bold text-blue-900 dark:text-blue-100 mt-2">{{ $totalSeniorCitizens }}</p>
                            <p class="text-blue-600 dark:text-blue-400 text-xs mt-2">Active senior citizens</p>
                        </div>
                        <span class="text-3xl">👥</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-green-600 dark:text-green-400 text-sm font-semibold uppercase tracking-wide">Social Pension</p>
                            <p class="text-4xl font-bold text-green-900 dark:text-green-100 mt-2">{{ $socialPensionCount }}</p>
                            <p class="text-green-600 dark:text-green-400 text-xs mt-2">{{ round(($socialPensionCount / max(1, $totalSeniorCitizens)) * 100, 1) }}% of total</p>
                        </div>
                        <span class="text-3xl">💰</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-yellow-600 dark:text-yellow-400 text-sm font-semibold uppercase tracking-wide">Waitlist</p>
                            <p class="text-4xl font-bold text-yellow-900 dark:text-yellow-100 mt-2">{{ $waitlistCount }}</p>
                            <p class="text-yellow-600 dark:text-yellow-400 text-xs mt-2">Pending approval</p>
                        </div>
                        <span class="text-3xl">📋</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-purple-600 dark:text-purple-400 text-sm font-semibold uppercase tracking-wide">With Disability</p>
                            <p class="text-4xl font-bold text-purple-900 dark:text-purple-100 mt-2">{{ \App\Models\SeniorCitizen::where('with_disability', true)->count() }}</p>
                            <p class="text-purple-600 dark:text-purple-400 text-xs mt-2">Special assistance</p>
                        </div>
                        <span class="text-3xl">♿</span>
                    </div>
                </div>
            </div>

            <!-- Key Metrics -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Pension Types Distribution -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">Pension Types</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Distribution of pension sources</p>
                    
                    <div class="space-y-4">
                        @php
                            $total = max(1, $totalSeniorCitizens);
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">SSS</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $sssCount }} ({{ round(($sssCount / $total) * 100, 1) }}%)</span>
                            </div>
                            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600" style="width: {{ ($sssCount / $total) * 100 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">GSIS</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $gsisCount }} ({{ round(($gsisCount / $total) * 100, 1) }}%)</span>
                            </div>
                            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-green-400 to-green-600" style="width: {{ ($gsisCount / $total) * 100 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">PVAO</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $pvaoCount }} ({{ round(($pvaoCount / $total) * 100, 1) }}%)</span>
                            </div>
                            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-red-400 to-red-600" style="width: {{ ($pvaoCount / $total) * 100 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Family Pension</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\SeniorCitizen::where('family_pension', true)->count() }}</span>
                            </div>
                            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-600" style="width: {{ ((\App\Models\SeniorCitizen::where('family_pension', true)->count()) / $total) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sex Distribution -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">Sex Distribution</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Gender breakdown</p>
                    
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Male</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $maleCount }} ({{ round(($maleCount / $total) * 100, 1) }}%)</span>
                            </div>
                            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600" style="width: {{ ($maleCount / $total) * 100 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Female</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $femaleCount }} ({{ round(($femaleCount / $total) * 100, 1) }}%)</span>
                            </div>
                            <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-pink-400 to-pink-600" style="width: {{ ($femaleCount / $total) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-xs text-gray-600 dark:text-gray-400"><strong>Tip:</strong> Use detailed reports to filter by specific demographics.</p>
                    </div>
                </div>

                <!-- Health Status Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">Health Status</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Special health conditions</p>
                    
                    <div class="space-y-3">
                        @php
                            $withDisability = \App\Models\SeniorCitizen::where('with_disability', true)->count();
                            $bedridden = \App\Models\SeniorCitizen::where('bedridden', true)->count();
                            $criticalIllness = \App\Models\SeniorCitizen::where('with_critical_illness', true)->count();
                            $assistive = \App\Models\SeniorCitizen::where('with_assistive_device', true)->count();
                        @endphp

                        <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">With Disability</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $withDisability }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Bedridden</span>
                            <span class="text-lg font-bold text-orange-600 dark:text-orange-400">{{ $bedridden }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Critical Illness</span>
                            <span class="text-lg font-bold text-red-600 dark:text-red-400">{{ $criticalIllness }}</span>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Assistive Device</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $assistive }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Report Cards -->
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Detailed Reports</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('reports.health') }}" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-blue-400 dark:hover:border-blue-600 transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 group-hover:text-blue-600 transition">Health Conditions</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Filter by disability, critical illness, and assistive devices. Detailed metrics on health status.</p>
                            </div>
                            <span class="text-3xl">🏥</span>
                        </div>
                        <div class="mt-4 inline-flex items-center text-blue-600 dark:text-blue-400 font-semibold text-sm group-hover:gap-2 gap-1 transition">
                            View Report →
                        </div>
                    </a>

                    <a href="{{ route('reports.barangay') }}" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-green-400 dark:hover:border-green-600 transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 group-hover:text-green-600 transition">By Barangay</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Analyze senior citizens grouped by barangay with location-specific insights.</p>
                            </div>
                            <span class="text-3xl">📍</span>
                        </div>
                        <div class="mt-4 inline-flex items-center text-green-600 dark:text-green-400 font-semibold text-sm group-hover:gap-2 gap-1 transition">
                            View Report →
                        </div>
                    </a>

                    <a href="{{ route('reports.deceased') }}" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg hover:border-red-400 dark:hover:border-red-600 transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 group-hover:text-red-600 transition">Archived Records</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">View deceased or archived records with option to restore if needed.</p>
                            </div>
                            <span class="text-3xl">📦</span>
                        </div>
                        <div class="mt-4 inline-flex items-center text-red-600 dark:text-red-400 font-semibold text-sm group-hover:gap-2 gap-1 transition">
                            View Report →
                        </div>
                    </a>
                </div>
            </div>

            <!-- Export & Advanced Options -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-6">Export & Advanced Reports</h3>
                
                <form method="GET" action="{{ route('reports.export') }}" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Filter by Sex (Optional)</label>
                            <select name="sex" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 transition">
                                <option value="">All Sexes</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Filter by Status (Optional)</label>
                            <select name="status" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 transition">
                                <option value="">All Status</option>
                                <option value="waitlist">Waitlist</option>
                                <option value="social_pension">Social Pension</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-green-600 dark:bg-green-700 text-white rounded-lg font-semibold hover:bg-green-700 dark:hover:bg-green-600 transition">
                            📥 Export to CSV
                        </button>
                        <a href="{{ route('reports.statistics') }}" class="px-6 py-2.5 bg-blue-600 dark:bg-blue-700 text-white rounded-lg font-semibold hover:bg-blue-700 dark:hover:bg-blue-600 transition">
                            📊 Detailed Statistics
                        </a>
                        <a href="{{ route('senior-citizens.index') }}" class="px-6 py-2.5 bg-gray-600 dark:bg-gray-700 text-white rounded-lg font-semibold hover:bg-gray-700 dark:hover:bg-gray-600 transition">
                            📋 View Masterlist
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
