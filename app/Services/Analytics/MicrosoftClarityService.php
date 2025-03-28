<?php

namespace App\Services\Analytics;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class MicrosoftClarityService implements AnalyticsServiceInterface
{
    protected $projectId;
    protected $apiKey;

    public function __construct()
    {
        $this->projectId = config('services.microsoft_clarity.project_id');
        $this->apiKey = config('services.microsoft_clarity.api_key');
    }

    public function fetchData(Carbon $date): array
    {
        return MockAnalyticsService::generateMockData('microsoft_clarity', $date);
    }
}
