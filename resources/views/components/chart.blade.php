@props(['id', 'title', 'labels', 'data', 'type' => 'pie'])

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
        
        const chartColors = {
            pie: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
            bar: ['#3b82f6', '#ef4444']
        };

        const config = {
            type: '{{ $type }}',
            data: {
                labels: @json($labels),
                datasets: [
                    {
                        label: '{{ $title }}',
                        data: @json($data),
                        backgroundColor: chartColors['{{ $type }}'],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        hoverBackgroundColor: '#1f2937'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: '{{ $type === "pie" ? "bottom" : "top" }}',
                        labels: {
                            color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#d1d5db' : '#374151',
                            font: { size: 12 },
                            padding: 15
                        }
                    }
                },
                @if($type === 'bar')
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#d1d5db' : '#374151' },
                        grid: { color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#374151' : '#e5e7eb' }
                    },
                    x: {
                        ticks: { color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#d1d5db' : '#374151' },
                        grid: { display: false }
                    }
                }
                @endif
            }
        };

        new Chart(ctx, config);
    });
</script>
@endpush
