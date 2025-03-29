<?php

namespace Database\Seeders;

use App\Models\AnalyticsData;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AnalyticsDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        AnalyticsData::truncate();

        $platforms = ['google_analytics', 'microsoft_clarity', 'facebook', 'instagram', 'snapchat'];
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        for ($date = clone $startDate; $date <= $endDate; $date->addDay()) {
            foreach ($platforms as $platform) {
                // Generate random numbers for metrics
                $profileVisits = rand(50, 500);
                $postVisits = rand(20, 200);
                $totalVisits = $profileVisits + $postVisits;
                $uniqueVisitors = rand(30, 300);
                $pageViews = rand(100, 1000);

                // Create analytics data record
                AnalyticsData::create([
                    'platform' => $platform,
                    'date' => $date->format('Y-m-d'),
                    'profile_visits' => $profileVisits,
                    'post_visits' => $postVisits,
                    'total_visits' => $totalVisits,
                    'unique_visitors' => $uniqueVisitors,
                    'page_views' => $pageViews,
                    'additional_metrics' => json_encode([
                        'bounce_rate' => rand(20, 80),
                        'avg_session_duration' => rand(1, 30),
                        'conversion_rate' => rand(1, 10),
                    ]),
                ]);
            }
        }
    }

    private function getPlatformMultiplier(string $platform): float
    {
        return match ($platform) {
            'google_analytics' => 1.5,    // Higher traffic
            'microsoft_clarity' => 0.8,   // Slightly lower traffic
            'facebook' => 1.2,            // Medium-high traffic
            'instagram' => 1.3,           // Medium-high traffic
            'snapchat' => 0.7,            // Lower traffic
            default => 1.0,
        };
    }
}
