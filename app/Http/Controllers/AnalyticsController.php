<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        // Get data for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $analyticsData = AnalyticsData::filterByDateRange($startDate, $endDate)
            ->get()
            ->groupBy('platform');

        // Prepare data for charts
        $chartData = [
            'labels' => [],
            'datasets' => [
                'total_visits' => [],
                'unique_visitors' => [],
                'page_views' => [],
            ],
        ];

        // Generate labels for the last 30 days
        $dateRange = [];
        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
            $dateRange[] = $date->format('Y-m-d');
            $chartData['labels'][] = $date->format('M d');
        }

        // Prepare data for each platform
        foreach ($analyticsData as $platform => $records) {
            $recordsByDate = $records->keyBy(function ($record) {
                return $record->date->format('Y-m-d');
            });

            $platformData = array_map(function ($date) use ($recordsByDate) {
                return $recordsByDate[$date]->total_visits ?? 0;
            }, $dateRange);

            $chartData['datasets']['total_visits'][] = [
                'label' => ucwords(str_replace('_', ' ', $platform)),
                'data' => $platformData,
                'borderColor' => $this->getPlatformColor($platform),
                'fill' => false,
            ];

            $platformData = array_map(function ($date) use ($recordsByDate) {
                return $recordsByDate[$date]->unique_visitors ?? 0;
            }, $dateRange);

            $chartData['datasets']['unique_visitors'][] = [
                'label' => ucwords(str_replace('_', ' ', $platform)),
                'data' => $platformData,
                'borderColor' => $this->getPlatformColor($platform),
                'fill' => false,
            ];

            $platformData = array_map(function ($date) use ($recordsByDate) {
                return $recordsByDate[$date]->page_views ?? 0;
            }, $dateRange);

            $chartData['datasets']['page_views'][] = [
                'label' => ucwords(str_replace('_', ' ', $platform)),
                'data' => $platformData,
                'borderColor' => $this->getPlatformColor($platform),
                'fill' => false,
            ];
        }

        // Get latest data for tables
        $latestData = AnalyticsData::whereDate('date', $endDate->format('Y-m-d'))->get();

        return view('analytics.dashboard', compact('chartData', 'latestData'));
    }

    public function filter(Request $request)
    {
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $data = AnalyticsData::filterByDateRange($startDate, $endDate)
            ->get()
            ->groupBy('platform');

        return response()->json([
            'platforms' => $data->keys(),
            'profile_visits' => $data->map(function ($records) {
                return $records->sum('profile_visits');
            }),
            'post_visits' => $data->map(function ($records) {
                return $records->sum('post_visits');
            }),
        ]);
    }

    public function social()
    {
        // Get data for the last 7 days
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        $data = AnalyticsData::filterByDateRange($startDate, $endDate)
            ->get()
            ->groupBy('platform');

        // Prepare data for social-specific charts
        $chartData = [
            'labels' => [],
            'datasets' => [
                'profile_visits' => [],
                'post_visits' => [],
            ],
        ];

        // Generate labels for the last 7 days
        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            $chartData['labels'][] = $date->format('M d');
        }

        // Prepare data for each platform
        foreach ($data as $platform => $records) {
            $chartData['datasets']['profile_visits'][] = [
                'label' => ucwords(str_replace('_', ' ', $platform)),
                'data' => $records->pluck('profile_visits')->toArray(),
                'borderColor' => $this->getPlatformColor($platform),
                'fill' => false,
            ];

            $chartData['datasets']['post_visits'][] = [
                'label' => ucwords(str_replace('_', ' ', $platform)),
                'data' => $records->pluck('post_visits')->toArray(),
                'borderColor' => $this->getPlatformColor($platform),
                'fill' => false,
            ];
        }

        // Get latest data for tables
        $latestData = AnalyticsData::where('date', $endDate->format('Y-m-d'))->get();

        return view('analytics.social', compact('chartData', 'latestData'));
    }

    private function getPlatformColor($platform)
    {
        $colors = [
            'google_analytics' => '#4285F4',
            'microsoft_clarity' => '#00A4EF',
            'facebook' => '#1877F2',
            'instagram' => '#E4405F',
            'snapchat' => '#FFFC00',
        ];

        return $colors[$platform] ?? '#000000';
    }
}
