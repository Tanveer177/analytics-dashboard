<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AnalyticsController extends Controller
{
    public function dashboard(Request $request)
    {
        try {
            // Get date range from request or use default 30 days
            $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
            $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : $endDate->copy()->subDays(30);

            // Ensure we have valid dates
            if ($startDate->isAfter($endDate)) {
                $temp = $startDate;
                $startDate = $endDate;
                $endDate = $temp;
            }

            // Query the data
            $analyticsData = AnalyticsData::whereBetween('date', [$startDate->startOfDay(), $endDate->endOfDay()])
                ->orderBy('date')
                ->get()
                ->groupBy('platform');

            if ($analyticsData->isEmpty()) {
                return view('dashboard', [
                    'chartData' => null,
                    'startDate' => $startDate->format('Y-m-d'),
                    'endDate' => $endDate->format('Y-m-d'),
                    'error' => 'No data available for the selected period.'
                ]);
            }

            // Prepare data for charts
            $chartData = [
                'labels' => [],
                'datasets' => [
                    'total_visits' => [],
                    'unique_visitors' => [],
                    'page_views' => [],
                ],
            ];

            // Generate labels for the date range
            $dateRange = [];
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $dateStr = $currentDate->format('Y-m-d');
                $dateRange[] = $dateStr;
                $chartData['labels'][] = $currentDate->format('M d');
                $currentDate->addDay();
            }

            // Prepare data for each platform
            foreach ($analyticsData as $platform => $records) {
                $recordsByDate = $records->keyBy(function ($record) {
                    return $record->date->format('Y-m-d');
                });

                // Prepare data arrays with 0 as default for missing dates
                $totalVisitsData = array_fill_keys($dateRange, 0);
                $uniqueVisitorsData = array_fill_keys($dateRange, 0);
                $pageViewsData = array_fill_keys($dateRange, 0);

                // Fill in actual values where we have data
                foreach ($recordsByDate as $date => $record) {
                    $totalVisitsData[$date] = $record->total_visits;
                    $uniqueVisitorsData[$date] = $record->unique_visitors;
                    $pageViewsData[$date] = $record->page_views;
                }

                $platformLabel = ucwords(str_replace('_', ' ', $platform));
                $platformColor = $this->getPlatformColor($platform);

                // Add datasets for each metric
                $chartData['datasets']['total_visits'][] = [
                    'label' => $platformLabel,
                    'data' => array_values($totalVisitsData),
                    'borderColor' => $platformColor,
                    'backgroundColor' => $this->hexToRgba($platformColor, 0.1),
                    'tension' => 0.4,
                    'fill' => true,
                ];

                $chartData['datasets']['unique_visitors'][] = [
                    'label' => $platformLabel,
                    'data' => array_values($uniqueVisitorsData),
                    'borderColor' => $platformColor,
                    'backgroundColor' => $this->hexToRgba($platformColor, 0.1),
                    'tension' => 0.4,
                    'fill' => true,
                ];

                $chartData['datasets']['page_views'][] = [
                    'label' => $platformLabel,
                    'data' => array_values($pageViewsData),
                    'borderColor' => $platformColor,
                    'backgroundColor' => $this->hexToRgba($platformColor, 0.1),
                    'tension' => 0.4,
                    'fill' => true,
                ];
            }

            return view('dashboard', [
                'chartData' => $chartData,
                'startDate' => $startDate->format('Y-m-d'),
                'endDate' => $endDate->format('Y-m-d')
            ]);
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return view('dashboard', [
                'chartData' => null,
                'error' => 'Error loading analytics data: ' . $e->getMessage()
            ]);
        }
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

    public function socialMedia()
    {
        $platforms = ['google_analytics', 'microsoft_clarity', 'facebook', 'instagram', 'snapchat'];
        $socialData = [];

        foreach ($platforms as $platform) {
            $configFile = "social_media/{$platform}.json";
            if (Storage::exists($configFile)) {
                $config = json_decode(Storage::get($configFile), true);
                $socialData[$platform] = [
                    'config' => $config,
                    'metrics' => $this->getSocialMediaMetrics($platform)
                ];
            }
        }

        return view('social-media', compact('socialData'));
    }

    private function getSocialMediaMetrics($platform)
    {
        // This would be replaced with actual API calls to each platform
        // For now, returning mock data
        return [
            'date' => Carbon::now()->format('Y-m-d'),
            'metrics' => [
                'total_visits' => rand(1000, 5000),
                'unique_visitors' => rand(500, 2000),
                'engagement_rate' => rand(1, 10) / 10,
            ]
        ];
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

    private function hexToRgba($hex, $alpha)
    {
        $hex = str_replace('#', '', $hex);
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        return "rgba($r, $g, $b, $alpha)";
    }
}
