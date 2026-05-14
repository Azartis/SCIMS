<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white">Analytics</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Age, barangay, deceased trends, and disability distribution.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 dark:text-blue-400 font-medium hover:underline">
                ← Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Age Distribution -->
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">{{ $healthReport['title'] ?? 'Age Distribution' }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Breakdown of seniors by age group.</p>
                    <div class="h-56">
                        <canvas id="ageReportChart"></canvas>
                    </div>
                </div>

                <!-- Barangay Distribution -->
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">{{ $barangayReport['title'] ?? 'Barangay Distribution' }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Top barangays by senior citizen count.</p>
                    <div class="h-56">
                        <canvas id="barangayReportChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Deceased last 12 months -->
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">{{ $deceasedReport['title'] ?? 'Deceased (Last 12 Months)' }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Monthly deaths among registered seniors.</p>
                    <div class="h-56">
                        <canvas id="deceasedReportChart"></canvas>
                    </div>
                </div>

                <!-- Disability by barangay -->
                <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
                    <h2 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">{{ $disabilityReport['title'] ?? 'Disability Distribution' }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Seniors with disability per barangay.</p>
                    <div class="h-56">
                        <canvas id="disabilityReportChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Age distribution
            const ageLabels = {!! json_encode(array_keys($healthReport['data'] ?? [])) !!};
            const ageData = {!! json_encode(array_values($healthReport['data'] ?? [])) !!};
            const ageCtx = document.getElementById('ageReportChart')?.getContext('2d');
            if (ageCtx) {
                new Chart(ageCtx, {
                    type: 'bar',
                    data: {
                        labels: ageLabels,
                        datasets: [{
                            label: 'Seniors',
                            data: ageData,
                            backgroundColor: '#3b82f6',
                            borderRadius: 6,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true },
                        },
                    },
                });
            }

            // Barangay distribution (top 10)
            const barangayData = {!! json_encode($barangayReport['data'] ?? []) !!};
            const barangayLabels = Object.keys(barangayData).slice(0, 10);
            const barangayCounts = Object.values(barangayData).slice(0, 10);
            const barangayCtx = document.getElementById('barangayReportChart')?.getContext('2d');
            if (barangayCtx) {
                new Chart(barangayCtx, {
                    type: 'bar',
                    data: {
                        labels: barangayLabels,
                        datasets: [{
                            label: 'Seniors',
                            data: barangayCounts,
                            backgroundColor: '#10b981',
                            borderRadius: 6,
                        }],
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                    },
                });
            }

            // Deceased last 12 months
            const deceasedArr = {!! json_encode($deceasedReport['data'] ?? []) !!};
            const deceasedLabels = deceasedArr.map(i => i.date ?? i['date']);
            const deceasedCounts = deceasedArr.map(i => i.count ?? i['count']);
            const deceasedCtx = document.getElementById('deceasedReportChart')?.getContext('2d');
            if (deceasedCtx) {
                new Chart(deceasedCtx, {
                    type: 'line',
                    data: {
                        labels: deceasedLabels,
                        datasets: [{
                            label: 'Deaths',
                            data: deceasedCounts,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239,68,68,0.15)',
                            fill: true,
                            tension: 0.3,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                    },
                });
            }

            // Disability by barangay
            const disBarangayData = {!! json_encode($disabilityReport['by_barangay'] ?? []) !!};
            const disLabels = Object.keys(disBarangayData).slice(0, 10);
            const disCounts = Object.values(disBarangayData).slice(0, 10);
            const disCtx = document.getElementById('disabilityReportChart')?.getContext('2d');
            if (disCtx) {
                new Chart(disCtx, {
                    type: 'bar',
                    data: {
                        labels: disLabels,
                        datasets: [{
                            label: 'With Disability',
                            data: disCounts,
                            backgroundColor: '#6366f1',
                            borderRadius: 6,
                        }],
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                    },
                });
            }
        </script>
    @endpush
</x-app-layout>

