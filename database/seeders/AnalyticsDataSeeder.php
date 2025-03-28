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

        // Set correct date range (last 30 days until yesterday)
        $endDate = Carbon::yesterday();
        $startDate = Carbon::yesterday()->subDays(30);

        $date = clone $startDate;
        while ($date <= $endDate) {
            foreach ($platforms as $platform) {
                // Base metrics
                $totalVisits = rand(100, 1000);
                $uniqueVisitors = rand(50, $totalVisits);
                $pageViews = rand($totalVisits, $totalVisits * 3);

                // Platform-specific adjustments
                $multiplier = $this->getPlatformMultiplier($platform);
                $totalVisits = (int)($totalVisits * $multiplier);
                $uniqueVisitors = (int)($uniqueVisitors * $multiplier);

                AnalyticsData::updateOrCreate(
                    [
                        'platform' => $platform,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'profile_visits' => (int)($totalVisits * rand(20, 40) / 100),
                        'post_visits' => (int)($totalVisits * rand(30, 50) / 100),
                        'total_visits' => $totalVisits,
                        'unique_visitors' => $uniqueVisitors,
                        'page_views' => $pageViews,
                        'additional_metrics' => [
                            'bounce_rate' => rand(20, 80),
                            'avg_session_duration' => rand(30, 300),
                            'conversion_rate' => rand(1, 10),
                        ],
                    ]
                );
            }
            $date->addDay();
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
