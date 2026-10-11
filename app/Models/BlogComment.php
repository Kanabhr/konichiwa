<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogComment extends Model
{
    use HasFactory;

    // cf-turnstile-response, website, and website_url are intentionally absent:
    // the Turnstile token is only used for validation and must never be persisted,
    // website is silently discarded (no website field in the form), and website_url
    // is the honeypot field whose value is never stored.
    protected $fillable = [
        'blog_id',
        'name',
        'email',
        'message',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }
}
