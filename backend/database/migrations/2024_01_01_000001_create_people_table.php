<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * TODO: Design your database schema for people/characters from SWAPI.
     *
     * Consider:
     * - What fields from SWAPI do you need? https://swapi.tech/documentation
     * - What data types are appropriate?
     * - Which fields should be nullable?
     * - What indexes do you need for search performance?
     * - How will you track the SWAPI source data?
     *
     * Example fields you might want:
     * - name, height, mass, hair_color, eye_color, birth_year, gender
     * - homeworld, swapi_url (for tracking source data)
     *
     * Remember to add indexes for fields you'll search on!
     */
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();

            // TODO: Add your columns here
            // Example: $table->string('name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
