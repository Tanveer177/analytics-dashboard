<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [AnalyticsController::class, 'dashboard'])->name('dashboard');

    // Social Media Analytics route
    Route::get('/social-media', [AnalyticsController::class, 'socialMedia'])->name('social.media');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Add this temporary route for testing
Route::get('/test-data', function () {
    $count = \App\Models\AnalyticsData::count();
    $first = \App\Models\AnalyticsData::first();
    $platforms = \App\Models\AnalyticsData::distinct('platform')->pluck('platform');

    return [
        'total_records' => $count,
        'first_record' => $first,
        'platforms' => $platforms
    ];
});

require __DIR__ . '/auth.php';
