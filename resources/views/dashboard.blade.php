@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-4">Analytics Dashboard</h2>

                @if(isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
                @endif

                <!-- Date Range Filter -->
                <div class="mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDate ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex-1">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex items-end">
                            <button id="filterBtn" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Filter
                            </button>
                        </div>
                    </div>
                </div>

                @if(!$chartData)
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">No data available for the selected period.</span>
                </div>
                @else
                <!-- Total Visits Chart -->
                <div class="mb-8 bg-gray-50 p-4 rounded-lg shadow">
                    <h3 class="text-xl font-semibold mb-4">Total Visits</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="totalVisitsChart"></canvas>
                    </div>
                </div>

                <!-- Unique Visitors Chart -->
                <div class="mb-8 bg-gray-50 p-4 rounded-lg shadow">
                    <h3 class="text-xl font-semibold mb-4">Unique Visitors</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="uniqueVisitorsChart"></canvas>
                    </div>
                </div>

                <!-- Page Views Chart -->
                <div class="mb-8 bg-gray-50 p-4 rounded-lg shadow">
                    <h3 class="text-xl font-semibold mb-4">Page Views</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="pageViewsChart"></canvas>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = @json($chartData ?? null);

        if (!chartData) return;

        const commonOptions = {
            responsive: true
            , maintainAspectRatio: false
            , plugins: {
                legend: {
                    position: 'top'
                    , labels: {
                        padding: 20
                        , usePointStyle: true
                    , }
                }
                , tooltip: {
                    mode: 'index'
                    , intersect: false
                    , backgroundColor: 'rgba(255, 255, 255, 0.9)'
                    , titleColor: '#000'
                    , bodyColor: '#666'
                    , borderColor: '#ddd'
                    , borderWidth: 1
                    , padding: 10
                    , displayColors: true
                , }
            }
            , interaction: {
                mode: 'nearest'
                , axis: 'x'
                , intersect: false
            }
            , scales: {
                y: {
                    beginAtZero: true
                    , grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    , }
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
        };

        // Create charts
        new Chart(document.getElementById('totalVisitsChart'), {
            type: 'line'
            , data: {
                labels: chartData.labels
                , datasets: chartData.datasets.total_visits
            }
            , options: commonOptions
        });

        new Chart(document.getElementById('uniqueVisitorsChart'), {
            type: 'line'
            , data: {
                labels: chartData.labels
                , datasets: chartData.datasets.unique_visitors
            }
            , options: commonOptions
        });

        new Chart(document.getElementById('pageViewsChart'), {
            type: 'line'
            , data: {
                labels: chartData.labels
                , datasets: chartData.datasets.page_views
            }
            , options: commonOptions
        });

        // Date Filter Functionality
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const filterBtn = document.getElementById('filterBtn');

        // Set default dates if not already set
        if (!startDateInput.value || !endDateInput.value) {
            const today = new Date();
            const thirtyDaysAgo = new Date();
            thirtyDaysAgo.setDate(today.getDate() - 30);

            startDateInput.value = thirtyDaysAgo.toISOString().split('T')[0];
            endDateInput.value = today.toISOString().split('T')[0];
        }

        filterBtn.addEventListener('click', function() {
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            if (startDate && endDate) {
                window.location.href = `/dashboard?start_date=${startDate}&end_date=${endDate}`;
            } else {
                alert('Please select both start and end dates');
            }
        });
    });

</script>
@endpush
@endsection
