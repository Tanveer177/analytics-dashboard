<?php

namespace App\Console\Commands;

use App\Models\AnalyticsData;
use App\Services\Analytics\GoogleAnalyticsService;
use App\Services\Analytics\MicrosoftClarityService;
use App\Services\Analytics\FacebookService;
use App\Services\Analytics\InstagramService;
use App\Services\Analytics\SnapchatService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class FetchAnalyticsData extends Command
{
    protected $signature = 'analytics:fetch {--platform=all} {--date=}';
    protected $description = 'Fetch analytics data from various platforms';

    protected $services = [
        'google_analytics' => GoogleAnalyticsService::class,
        'microsoft_clarity' => MicrosoftClarityService::class,
        'facebook' => FacebookService::class,
        'instagram' => InstagramService::class,
        'snapchat' => SnapchatService::class,
    ];

    public function handle()
    {
        $platform = $this->option('platform');
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::yesterday();

        $this->info("Fetching analytics data for date: {$date->format('Y-m-d')}");

        if ($platform === 'all') {
            foreach ($this->services as $platformName => $serviceClass) {
                $this->fetchDataForPlatform($platformName, $serviceClass, $date);
            }
        } else {
            if (!isset($this->services[$platform])) {
                $this->error("Invalid platform: {$platform}");
                return 1;
            }
            $this->fetchDataForPlatform($platform, $this->services[$platform], $date);
        }

        $this->info('Analytics data fetch completed successfully!');
    }

    protected function fetchDataForPlatform($platform, $serviceClass, $date)
    {
        try {
            $this->info("Fetching data from {$platform}...");
            $service = new $serviceClass();
            $data = $service->fetchData($date);

            AnalyticsData::updateOrCreate(
                [
                    'platform' => $platform,
                    'date' => $date,
                ],
                [
                    'profile_visits' => $data['profile_visits'] ?? 0,
                    'post_visits' => $data['post_visits'] ?? 0,
                    'total_visits' => $data['total_visits'] ?? 0,
                    'unique_visitors' => $data['unique_visitors'] ?? 0,
                    'page_views' => $data['page_views'] ?? 0,
                    'additional_metrics' => $data['additional_metrics'] ?? null,
                ]
            );

            $this->info("Successfully fetched and stored data from {$platform}");
        } catch (\Exception $e) {
            $this->error("Error fetching data from {$platform}: {$e->getMessage()}");
        }
    }
}
