<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Blog;
use App\Models\BlogComment;

class BlogCommentController extends Controller
{
    public function store(StoreCommentRequest $request, Blog $blog)
    {
        // Honeypot: if the hidden website_url field is filled, silently return
        // the success response without storing anything (Requirements: 5.4)
        if ($request->filled('website_url')) {
            return redirect()
                ->route('blog-details', $blog->slug)
                ->with('comment_success', 'Thank you! Your comment has been submitted and is awaiting approval.');
        }

        // Only $request->validated() fields are stored — cf-turnstile-response,
        // website, and website_url are intentionally absent from $fillable (Requirement: 4.6)
        BlogComment::create(array_merge(
            $request->validated(),
            ['blog_id' => $blog->id, 'approved' => false]
        ));

        return redirect()
            ->route('blog-details', $blog->slug)
            ->with('comment_success', 'Thank you! Your comment has been submitted and is awaiting approval.');
    }
}
