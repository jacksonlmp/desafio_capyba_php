<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['title', 'description', 'release_date', 'is_featured', 'genre'];

    public function scopeSearch($query, $term)
    {
        return $query->where('title', 'LIKE', "%$term%")
                     ->orWhere('description', 'LIKE', "%$term%");
    }

    public function scopeFilterByFeatured($query, $isFeatured)
    {
        return $query->where('is_featured', $isFeatured);
    }

    public function scopeOrdered($query, $field, $direction)
    {
        return $query->orderBy($field, $direction);
    }
}

