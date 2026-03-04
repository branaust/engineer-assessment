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
            $table->string('title');
            $table->unsignedTinyInteger('episode_id')->unique();
            $table->text('opening_crawl')->nullable();
            $table->string('director')->nullable();
            $table->string('producer')->nullable();
            $table->date('release_date')->nullable();
            $table->string('swapi_url')->nullable()->unique();
            $table->timestamps();
            $table->index('title');
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
