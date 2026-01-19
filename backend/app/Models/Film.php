<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Film Model
 *
 * Represents a Star Wars film from SWAPI.
 *
 * TODO: Configure this model based on your database schema.
 */
class Film extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * Add the fields that can be filled via create() or update()
     * Example: ['title', 'episode_id', 'director', 'release_date']
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * TODO: Define relationship to people (many-to-many).
     *
     * Example implementation:
     * public function people(): BelongsToMany
     * {
     *     return $this->belongsToMany(Person::class, 'film_person');
     * }
     */
}
