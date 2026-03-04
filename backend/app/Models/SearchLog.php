<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * SearchLog Model
 *
 * Tracks search queries for analytics purposes.
 */
class SearchLog extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'query', 'resource_type', 'results_count',
        'duration_ms', 'ip_address', 'user_agent', 'searched_at',
    ];

    protected $casts = [
        'searched_at'   => 'datetime',
        'duration_ms'   => 'integer',
        'results_count' => 'integer',
    ];
}
