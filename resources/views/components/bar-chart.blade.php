@props(['id', 'title', 'quarters', 'claimed', 'unclaimed'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ $title }}</h3>
    <div class="relative h-64">
        <canvas id="{{ $id }}"></canvas>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('{{ $id }}').getContext('2d');
        const isDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($quarters),
                datasets: [
                    {
                        label: 'Claimed',
                        data: @json($claimed),
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Unclaimed',
                        data: @json($unclaimed),
                        backgroundColor: '#ef4444',
                        borderRadius: 6,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: isDarkMode ? '#d1d5db' : '#374151',
                            font: { size: 12 },
                            padding: 15,
                            usePointStyle: true
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            color: isDarkMode ? '#d1d5db' : '#374151',
                            stepSize: 1
                        },
                        grid: { 
                            color: isDarkMode ? '#374151' : '#e5e7eb'
                        }
                    },
                    x: {
                        ticks: { color: isDarkMode ? '#d1d5db' : '#374151' },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endpush
