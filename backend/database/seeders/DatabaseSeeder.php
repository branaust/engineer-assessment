<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * 
     * This will seed the database with Star Wars data from SWAPI.
     * Make sure you have a working internet connection.
     * 
     * Run: php artisan db:seed
     */
    public function run(): void
    {
        $this->command->info('🌟 Seeding Star Wars data from SWAPI...');
        
        // Seed SWAPI data (people, films, relationships)
        $this->call([
            SwapiSeeder::class,
        ]);
        
        $this->command->info('✅ Database seeding completed!');
    }
}
