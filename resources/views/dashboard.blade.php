<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">

            <div class="flex items-center space-x-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date Range:</label>
                    <input type="date" id="startDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">To:</label>
                    <input type="date" id="endDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                </div>
                <div class="flex items-end">
                    <button onclick="filterData()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Apply Filter
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">Profile Visits</h3>
                            <div style="height: 300px;">
                                <canvas id="profileVisitsChart"></canvas>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">Post Visits</h3>
                            <div style="height: 300px;">
                                <canvas id="postVisitsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const analyticsData = @json($analyticsData);
        const platforms = Object.keys(analyticsData);

        // Profile Visits Chart
        var profileCtx = document.getElementById('profileVisitsChart').getContext('2d');
        var profileChart = new Chart(profileCtx, {
            type: 'bar'
            , data: {
                labels: platforms
                , datasets: [{
                    label: 'Profile Visits'
                    , data: platforms.map(platform => {
                        return analyticsData[platform].reduce((sum, record) => sum + record.profile_visits, 0);
                    })
                    , backgroundColor: 'rgba(54, 162, 235, 0.5)'
                    , borderColor: 'rgba(54, 162, 235, 1)'
                    , borderWidth: 1
                }]
            }
            , options: {
                responsive: true
                , maintainAspectRatio: false
                , scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Post Visits Chart
        var postCtx = document.getElementById('postVisitsChart').getContext('2d');
        var postChart = new Chart(postCtx, {
            type: 'line'
            , data: {
                labels: platforms
                , datasets: [{
                    label: 'Post Visits'
                    , data: platforms.map(platform => {
                        return analyticsData[platform].reduce((sum, record) => sum + record.post_visits, 0);
                    })
                    , fill: false
                    , borderColor: 'rgba(255, 99, 132, 1)'
                    , tension: 0.1
                }]
            }
            , options: {
                responsive: true
                , maintainAspectRatio: false
                , scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        function filterData() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            fetch(`{{ route('analytics.filter') }}?start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    updateCharts(data);
                });
        }

        function updateCharts(data) {
            profileChart.data.labels = data.platforms;
            profileChart.data.datasets[0].data = data.profile_visits;
            profileChart.update();

            postChart.data.labels = data.platforms;
            postChart.data.datasets[0].data = data.post_visits;
            postChart.update();
        }

    </script>
    @endpush
</x-app-layout>
