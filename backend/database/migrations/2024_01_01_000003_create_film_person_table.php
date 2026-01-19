<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * TODO: Create a pivot table for the many-to-many relationship between films and people.
     *
     * Consider:
     * - Foreign keys to both tables
     * - Unique constraint to prevent duplicates
     * - Indexes for query performance
     * - Cascade deletes
     *
     * Laravel naming convention: singular_singular in alphabetical order
     * Example: film_person (not films_people or person_film)
     */
    public function up(): void
    {
        Schema::create('film_person', function (Blueprint $table) {
            $table->id();

            // TODO: Add foreign keys and constraints here
            // Example: $table->foreignId('film_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('film_person');
    }
};
