<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->input('search', '');
        $category = $request->input('category', '');

        $query = Blog::published()->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title',   'like', '%' . $search . '%')
                  ->orWhere('excerpt', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $blogs = $query->paginate(9)->withQueryString();

        $categories = Blog::published()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        $recentPosts = Blog::published()->latest()->limit(3)->get();

        return view('blog-list', compact('blogs', 'search', 'categories', 'recentPosts'));
    }

    public function show($slug = null)
    {
        if (!$slug) {
            return redirect()->route('blog-list');
        }

        // published() scope ensures draft posts return 404
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();
        $blog->incrementViews();

        // Fix 2.16: use whereNull() when category is null, where() otherwise
        $relatedQuery = Blog::published()->where('id', '!=', $blog->id);
        if (is_null($blog->category)) {
            $relatedQuery->whereNull('category');
        } else {
            $relatedQuery->where('category', $blog->category);
        }
        $relatedBlogs = $relatedQuery->limit(3)->get();

        $categories = Blog::published()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->pluck('category');

        $comments = $blog->comments()->approved()->latest()->get();

        return view('blog-details', compact('blog', 'relatedBlogs', 'categories', 'comments'));
    }
}
