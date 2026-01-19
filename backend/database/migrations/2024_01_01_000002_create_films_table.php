<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * TODO: Design your database schema for films from SWAPI.
     *
     * Consider:
     * - What fields from SWAPI do you need? https://swapi.tech/documentation
     * - What data types are appropriate?
     * - Which fields should be nullable?
     * - What indexes do you need for search performance?
     *
     * Example fields you might want:
     * - title, episode_id, opening_crawl, director, producer, release_date
     * - swapi_url (for tracking source data)
     *
     * Remember to add indexes for fields you'll search on!
     */
    public function up(): void
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();

            // TODO: Add your columns here
            // Example: $table->string('title');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
