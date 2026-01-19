<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * TODO: Design a table to track search queries for analytics.
     *
     * Required statistics to calculate:
     * - Average request duration (in milliseconds)
     * - Most popular search hour (0-23)
     * - Top 5 search queries with percentages
     *
     * Consider what fields you need to track:
     * - What was searched?
     * - When was it searched?
     * - How long did it take?
     * - What was searched (people, films)?
     * - How many results were returned?
     *
     * Think about indexes for analytics queries!
     */
    public function up(): void
    {
        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();

            // TODO: Add columns for tracking searches
            // Consider: query, resource_type, duration_ms, searched_at, etc.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_logs');
    }
};
