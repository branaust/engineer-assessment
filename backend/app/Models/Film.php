<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'episode_id', 'opening_crawl', 'director',
        'producer', 'release_date', 'swapi_url',
    ];

    protected $casts = [
        'release_date' => 'date',
        'episode_id'   => 'integer',
    ];

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class, 'film_person');
    }
}
