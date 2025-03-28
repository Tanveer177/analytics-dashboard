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
                        <div class="p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">Total Visits</h3>
                            <canvas id="totalVisitsChart"></canvas>
                        </div>

                        <!-- Unique Visitors Chart -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">Unique Visitors</h3>
                            <canvas id="uniqueVisitorsChart"></canvas>
                        </div>

                        <!-- Page Views Chart -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">Page Views</h3>
                            <canvas id="pageViewsChart"></canvas>
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

        // Create charts for each metric
        const createChart = (elementId, dataset, label) => {
            const ctx = document.getElementById(elementId).getContext('2d');
            new Chart(ctx, {
                type: 'line'
                , data: {
                    labels: chartData.labels
                    , datasets: chartData.datasets[dataset]
                }
                , options: {
                    responsive: true
                    , title: {
                        display: true
                        , text: label
                    }
                }
            });
        };

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            createChart('totalVisitsChart', 'total_visits', 'Total Visits');
            createChart('uniqueVisitorsChart', 'unique_visitors', 'Unique Visitors');
            createChart('pageViewsChart', 'page_views', 'Page Views');
        });

    </script>
    @endpush
</x-app-layout>
