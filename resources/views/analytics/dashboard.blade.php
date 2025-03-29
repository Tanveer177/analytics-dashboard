<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Analytics Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Charts Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Total Visits Chart -->
                        <div class="p-6 rounded-lg shadow bg-white">
                            <h3 class="text-lg font-semibold mb-4">Total Visits</h3>
                            <div style="height: 300px;">
                                <canvas id="totalVisitsChart"></canvas>
                            </div>
                        </div>

                        <!-- Unique Visitors Chart -->
                        <div class="p-6 rounded-lg shadow bg-white">
                            <h3 class="text-lg font-semibold mb-4">Unique Visitors</h3>
                            <div style="height: 300px;">
                                <canvas id="uniqueVisitorsChart"></canvas>
                            </div>
                        </div>

                        <!-- Page Views Chart -->
                        <div class="p-6 rounded-lg shadow bg-white">
                            <h3 class="text-lg font-semibold mb-4">Page Views</h3>
                            <div style="height: 300px;">
                                <canvas id="pageViewsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Latest Data Table -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Latest Analytics Data</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platform</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Visits</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unique Visitors</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page Views</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bounce Rate</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($latestData as $data)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ ucwords(str_replace('_', ' ', $data->platform)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($data->total_visits) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($data->unique_visitors) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($data->page_views) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $data->additional_metrics['bounce_rate'] ?? 0 }}%
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = @json($chartData);

        // Debug data
        console.log('Chart Data:', chartData);
        console.log('Labels:', chartData.labels);
        console.log('Total Visits Data:', chartData.datasets.total_visits);

        // Create charts for each metric
        const createChart = (elementId, dataset, label) => {
            console.log(`Creating chart for ${elementId} with dataset:`, chartData.datasets[dataset]);
            const ctx = document.getElementById(elementId).getContext('2d');
            new Chart(ctx, {
                type: 'line'
                , data: {
                    labels: chartData.labels
                    , datasets: chartData.datasets[dataset].map(dataset => ({
                        ...dataset
                        , tension: 0.4
                        , borderWidth: 2
                        , pointRadius: 3
                        , pointHoverRadius: 5
                        , backgroundColor: dataset.borderColor.replace(')', ', 0.1)')
                    , }))
                }
                , options: {
                    responsive: true
                    , maintainAspectRatio: false
                    , plugins: {
                        title: {
                            display: true
                            , text: label
                            , font: {
                                size: 16
                                , weight: 'bold'
                            }
                            , padding: {
                                top: 10
                                , bottom: 10
                            }
                        }
                        , legend: {
                            position: 'top'
                            , labels: {
                                padding: 20
                                , usePointStyle: true
                                , pointStyle: 'circle'
                            }
                        }
                        , tooltip: {
                            mode: 'index'
                            , intersect: false
                            , backgroundColor: 'rgba(255, 255, 255, 0.9)'
                            , titleColor: '#000'
                            , bodyColor: '#000'
                            , borderColor: '#ddd'
                            , borderWidth: 1
                            , padding: 10
                            , displayColors: true
                            , callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.parsed.y.toLocaleString()} visits`;
                                }
                            }
                        }
                    }
                    , scales: {
                        y: {
                            beginAtZero: true
                            , grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                                , drawBorder: false
                            }
                            , ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                        , x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                    , interaction: {
                        mode: 'nearest'
                        , axis: 'x'
                        , intersect: false
                    }
                }
            });
        };

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            try {
                createChart('totalVisitsChart', 'total_visits', 'Total Visits');
                createChart('uniqueVisitorsChart', 'unique_visitors', 'Unique Visitors');
                createChart('pageViewsChart', 'page_views', 'Page Views');
            } catch (error) {
                console.error('Error creating charts:', error);
            }
        });

    </script>
    @endpush
</x-app-layout>
