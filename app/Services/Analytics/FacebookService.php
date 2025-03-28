<?php

namespace App\Services\Analytics;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class FacebookService implements AnalyticsServiceInterface
{
    protected $appId;
    protected $appSecret;
    protected $accessToken;

    public function __construct()
    {
        $this->appId = config('services.facebook.app_id');
        $this->appSecret = config('services.facebook.app_secret');
        $this->accessToken = config('services.facebook.access_token');
    }

    public function fetchData(Carbon $date): array
    {
        return MockAnalyticsService::generateMockData('facebook', $date);
    }
}
