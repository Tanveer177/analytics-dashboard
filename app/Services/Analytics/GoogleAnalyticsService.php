<?php

namespace App\Services\Analytics;

use Carbon\Carbon;

class GoogleAnalyticsService implements AnalyticsServiceInterface
{
    public function fetchData(Carbon $date): array
    {
        return MockAnalyticsService::generateMockData('google_analytics', $date);
    }
}
