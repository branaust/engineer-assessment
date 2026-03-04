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
            $table->string('query')->nullable();
            $table->string('resource_type', 20);
            $table->unsignedInteger('results_count')->default(0);
            $table->unsignedInteger('duration_ms');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('searched_at')->useCurrent();
            $table->timestamps();
            $table->index('searched_at');
            $table->index('query');
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
