<?php

namespace App\Services\Analytics;

use Carbon\Carbon;

interface AnalyticsServiceInterface
{
    /**
     * Fetch analytics data for a specific date
     *
     * @param Carbon $date
     * @return array
     */
    public function fetchData(Carbon $date): array;
}
