<?php

namespace App\Services\Analytics;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class SnapchatService implements AnalyticsServiceInterface
{
    protected $clientId;
    protected $clientSecret;
    protected $accessToken;

    public function __construct()
    {
        $this->clientId = config('services.snapchat.client_id');
        $this->clientSecret = config('services.snapchat.client_secret');
        $this->accessToken = config('services.snapchat.access_token');
    }

    public function fetchData(Carbon $date): array
    {
        return MockAnalyticsService::generateMockData('snapchat', $date);
    }
}
