<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('analytics_data', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // google_analytics, microsoft_clarity, facebook, instagram, snapchat
            $table->date('date');
            $table->integer('profile_visits')->default(0);
            $table->integer('post_visits')->default(0);
            $table->integer('total_visits')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->integer('page_views')->default(0);
            $table->json('additional_metrics')->nullable();
            $table->timestamps();

            $table->unique(['platform', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('analytics_data');
    }
};
