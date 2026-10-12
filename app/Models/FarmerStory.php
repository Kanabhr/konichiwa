<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_name',
        'mobile_number',
        'suggested_product',
        'location',
        'testimonial',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /** Scope: only active/visible stories. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
