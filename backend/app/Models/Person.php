<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Person Model
 *
 * Represents a Star Wars character from SWAPI.
 *
 * TODO: Configure this model based on your database schema.
 */
class Person extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'people';

    /**
     * The attributes that are mass assignable.
     *
     * Add the fields that can be filled via create() or update()
     * Example: ['name', 'height', 'mass', 'birth_year']
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * TODO: Define relationship to films (many-to-many).
     *
     * Example implementation:
     * public function films(): BelongsToMany
     * {
     *     return $this->belongsToMany(Film::class, 'film_person');
     * }
     */
}
