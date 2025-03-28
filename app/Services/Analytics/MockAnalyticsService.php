<?php

namespace App\Services\Analytics;

use Carbon\Carbon;

class MockAnalyticsService
{
    /**
     * Generate realistic mock analytics data
     *
     * @param string $platform
     * @param Carbon $date
     * @return array
     */
    public static function generateMockData(string $platform, Carbon $date): array
    {
        // Generate random but realistic numbers
        $totalVisits = rand(100, 1000);
        $uniqueVisitors = rand(50, $totalVisits);
        $pageViews = rand($totalVisits, $totalVisits * 3);

        // Profile visits are typically 20-40% of total visits
        $profileVisits = rand((int)($totalVisits * 0.2), (int)($totalVisits * 0.4));

        // Post visits are typically 30-50% of total visits
        $postVisits = rand((int)($totalVisits * 0.3), (int)($totalVisits * 0.5));

        // Platform-specific adjustments
        switch ($platform) {
            case 'google_analytics':
                // Google Analytics typically has higher numbers
                $totalVisits *= 1.5;
                $uniqueVisitors *= 1.5;
                $pageViews *= 1.5;
                break;
            case 'microsoft_clarity':
                // Microsoft Clarity might have slightly lower numbers
                $totalVisits *= 0.8;
                $uniqueVisitors *= 0.8;
                $pageViews *= 0.8;
                break;
            case 'facebook':
                // Facebook might have more profile visits
                $profileVisits *= 1.5;
                break;
            case 'instagram':
                // Instagram might have more post visits
                $postVisits *= 1.5;
                break;
            case 'snapchat':
                // Snapchat might have lower overall numbers
                $totalVisits *= 0.7;
                $uniqueVisitors *= 0.7;
                $pageViews *= 0.7;
                break;
        }

        return [
            'profile_visits' => (int)$profileVisits,
            'post_visits' => (int)$postVisits,
            'total_visits' => (int)$totalVisits,
            'unique_visitors' => (int)$uniqueVisitors,
            'page_views' => (int)$pageViews,
            'additional_metrics' => [
                'bounce_rate' => rand(30, 70),
                'avg_session_duration' => rand(60, 300),
                'conversion_rate' => rand(1, 5),
                'platform_specific_metric' => rand(100, 1000),
            ],
        ];
    }
}
