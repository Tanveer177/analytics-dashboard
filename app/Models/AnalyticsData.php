<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnalyticsData extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'date',
        'profile_visits',
        'post_visits',
        'total_visits',
        'unique_visitors',
        'page_views',
        'additional_metrics'
    ];

    protected $casts = [
        'date' => 'date',
        'additional_metrics' => 'array'
    ];

    public function scopeFilterByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByPlatform($query, $platform)
    {
        return $query->where('platform', $platform);
    }
}
