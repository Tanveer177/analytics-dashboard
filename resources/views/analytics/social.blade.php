<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Social Media Analytics
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Charts Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Profile Visits Chart -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">Profile Visits</h3>
                            <canvas id="profileVisitsChart"></canvas>
                        </div>
                        

                        <!-- Post Visits Chart -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">Post Visits</h3>
                            <canvas id="postVisitsChart"></canvas>
                        </div>
                    </div>

                    <!-- Latest Data Table -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Latest Social Media Data</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platform</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile Visits</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Post Visits</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Visits</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conversion Rate</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($latestData as $data)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ ucwords(str_replace('_', ' ', $data->platform)) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($data->profile_visits) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($data->post_visits) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ number_format($data->total_visits) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $data->additional_metrics['conversion_rate'] ?? 0 }}%
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

        // Profile Visits Chart
        new Chart(document.getElementById('profileVisitsChart'), {
            type: 'line'
            , data: {
                labels: chartData.labels
                , datasets: chartData.datasets.profile_visits
            }
            , options: {
                responsive: true
                , plugins: {
                    legend: {
                        position: 'top'
                    , }
                }
            }
        });

        // Post Visits Chart
        new Chart(document.getElementById('postVisitsChart'), {
            type: 'line'
            , data: {
                labels: chartData.labels
                , datasets: chartData.datasets.post_visits
            }
            , options: {
                responsive: true
                , plugins: {
                    legend: {
                        position: 'top'
                    , }
                }
            }
        });

    </script>
    @endpush
</x-app-layout>
