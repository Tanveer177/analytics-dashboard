<?php

namespace Database\Factories;

use App\Models\AnalyticsData;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnalyticsData>
 */
class AnalyticsDataFactory extends Factory
{
    protected $model = AnalyticsData::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $platforms = ['google_analytics', 'microsoft_clarity', 'facebook', 'instagram', 'snapchat'];
        $totalVisits = $this->faker->numberBetween(100, 1000);

        return [
            'platform' => $this->faker->randomElement($platforms),
            'date' => $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'profile_visits' => (int)($totalVisits * $this->faker->numberBetween(20, 40) / 100),
            'post_visits' => (int)($totalVisits * $this->faker->numberBetween(30, 50) / 100),
            'total_visits' => $totalVisits,
            'unique_visitors' => $this->faker->numberBetween(50, $totalVisits),
            'page_views' => $this->faker->numberBetween($totalVisits, $totalVisits * 3),
            'additional_metrics' => [
                'bounce_rate' => $this->faker->numberBetween(20, 80),
                'avg_session_duration' => $this->faker->numberBetween(30, 300),
            ],
        ];
    }
}
