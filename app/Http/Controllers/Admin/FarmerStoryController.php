<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FarmerStory;
use Illuminate\Http\Request;

class FarmerStoryController extends Controller
{
    public function index()
    {
        $stories = FarmerStory::latest()->get();
        return view('admin.farmer-stories.index', compact('stories'));
    }

    public function create()
    {
        return view('admin.farmer-stories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'farmer_name'       => 'required|string|max:150',
            'mobile_number'     => 'required|string|max:15',
            'suggested_product' => 'required|string|max:255',
            'location'          => 'nullable|string|max:255',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'is_active'         => 'boolean',
            'testimonial'       => 'nullable|string|max:300',
        ]);

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/farmer-stories'), $imageName);
            $validated['image'] = 'assets/images/farmer-stories/' . $imageName;
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        FarmerStory::create($validated);

        return redirect()
            ->route('admin.farmer-stories.index')
            ->with('success', 'Farmer story added successfully!');
    }

    public function edit(FarmerStory $farmerStory)
    {
        return view('admin.farmer-stories.edit', compact('farmerStory'));
    }

    public function update(Request $request, FarmerStory $farmerStory)
    {
        $validated = $request->validate([
            'farmer_name'       => 'required|string|max:150',
            'mobile_number'     => 'required|string|max:15',
            'suggested_product' => 'required|string|max:255',
            'location'          => 'nullable|string|max:255',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'is_active'         => 'boolean',
            'testimonial'       => 'nullable|string|max:300',
        ]);

        if ($request->hasFile('image')) {
            if ($farmerStory->image && file_exists(public_path($farmerStory->image))) {
                unlink(public_path($farmerStory->image));
            }
            $file      = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/images/farmer-stories'), $imageName);
            $validated['image'] = 'assets/images/farmer-stories/' . $imageName;
        }

        $validated['is_active'] = $request->boolean('is_active', $farmerStory->is_active);

        $farmerStory->update($validated);

        return redirect()
            ->route('admin.farmer-stories.index')
            ->with('success', 'Farmer story updated successfully!');
    }

    public function destroy(FarmerStory $farmerStory)
    {
        if ($farmerStory->image && file_exists(public_path($farmerStory->image))) {
            unlink(public_path($farmerStory->image));
        }
        $farmerStory->delete();

        return redirect()
            ->route('admin.farmer-stories.index')
            ->with('success', 'Farmer story deleted successfully!');
    }
}
