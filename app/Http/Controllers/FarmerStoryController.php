<?php

namespace App\Http\Controllers;

use App\Models\FarmerStory;
use Illuminate\Http\Request;

class FarmerStoryController extends Controller
{
    /**
     * Display the public farmer stories page.
     */
    public function index()
    {
        $farmerStories = FarmerStory::active()->latest()->get();
        return view('farmer-stories.index', compact('farmerStories'));
    }
}
