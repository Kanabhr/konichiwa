<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BlogComment;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'meta_description',
        'meta_keywords',
        'content',
        'image',
        'author',
        'category',
        'tags',
        'status',
        'views',
    ];

    protected $casts = [
        'views' => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    /**
     * Increment view count once per session per post.
     */
    public function incrementViews(): void
    {
        $sessionKey = 'viewed_blog_' . $this->id;
        if (!session()->has($sessionKey)) {
            $this->increment('views');
            session()->put($sessionKey, true);
        }
    }

    /**
     * Return tags as a trimmed array from the CSV string.
     * Access via $blog->tags_array anywhere without manual explode().
     */
    public function getTagsArrayAttribute(): array
    {
        if (!$this->tags) {
            return [];
        }
        return array_map('trim', explode(',', $this->tags));
    }
}
