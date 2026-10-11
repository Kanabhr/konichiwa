<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_blogs' => Blog::count(),
            'published_blogs' => Blog::where('status', 'published')->count(),
            'draft_blogs' => Blog::where('status', 'draft')->count(),
            'total_views' => Blog::sum('views'),
            'unread_messages' => ContactMessage::where('status', 'unread')->count(),
            'total_messages' => ContactMessage::count(),
        ];

        $recent_blogs = Blog::orderBy('created_at', 'desc')->limit(5)->get();
        $recent_messages = ContactMessage::orderBy('created_at', 'desc')->limit(5)->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recent_blogs' => $recent_blogs,
            'recent_messages' => $recent_messages,
        ]);
    }
}