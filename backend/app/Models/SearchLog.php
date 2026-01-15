<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * SearchLog Model
 * 
 * Tracks search queries for analytics purposes.
 * 
 * TODO: Configure this model based on your search_logs table schema.
 */
class SearchLog extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * Add the fields from your search_logs table
     * Example: ['query', 'resource_type', 'duration_ms', 'searched_at']
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    /**
     * TODO: Add casts for date fields and integers.
     * 
     * Example:
     * protected $casts = [
     *     'searched_at' => 'datetime',
     *     'duration_ms' => 'integer',
     * ];
     */
}

