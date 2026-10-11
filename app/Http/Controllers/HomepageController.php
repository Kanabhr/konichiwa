<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\FarmerStory;
use App\Models\Product;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function home(){
        $blogs = Blog::where('status', 'published')->latest()->limit(3)->get();
       $farmerStories = FarmerStory::active()->latest()->take(12)
    ->get(['id', 'farmer_name', 'suggested_product', 'location', 'image', 'testimonial']);
        return view('index', compact('blogs', 'farmerStories'));
    }
    public function products(){
        $products = Product::paginate(9);
        return view('product-catalog', compact('products'));
    }
   
}
