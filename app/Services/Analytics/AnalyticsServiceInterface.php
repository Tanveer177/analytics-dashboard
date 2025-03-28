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
     * @throws \Exception
     */
    public function fetchData(Carbon $date): array;

    /**
     * Validate the response data
     * @param array $data
     * @return bool
     */
    public function validateResponse(array $data): bool;
}
