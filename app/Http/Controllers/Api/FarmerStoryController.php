<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FarmerStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FarmerStoryController extends Controller
{
    /** GET /api/farmer-stories ? List all active farmer stories */
    public function index()
    {
        $stories = FarmerStory::active()->latest()->get([
            'id', 'farmer_name', 'mobile_number', 'suggested_product', 'location', 'image', 'created_at'
        ]);

        return response()->json([
            'success' => true,
            'data'    => 
$stories,
            'count'   => 
$stories->count(),
        ], 200);
    }

    /** POST /api/farmer-stories ? Create a new farmer story */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'farmer_name'       => 'required|string|max:150',
            'mobile_number'     => 'required|string|max:15',
            'suggested_product' => 'required|string|max:255',
            'location'          => 'nullable|string|max:255',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'is_active'         => 'boolean',
        ]);

        if (
$validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => 
$validator->errors(),
            ], 422);
        }

        
$data = $validator->validated();

        if (
$request->hasFile('image')) {
            
$file      = $request->file('image');
            
$imageName = time() . '_' . $file->getClientOriginalName();
            
$file->move(public_path('assets/images/farmer-stories'), $imageName);
            
$data['image'] = 'assets/images/farmer-stories/' . $imageName;
        }

        
$data['is_active'] = $request->input('is_active', true);

        
$story = FarmerStory::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Farmer story created successfully.',
            'data'    => 
$story,
        ], 201);
    }

    /** GET /api/farmer-stories/{id} ? Show a single farmer story */
    public function show(FarmerStory $farmerStory)
    {
        return response()->json([
            'success' => true,
            'data'    => 
$farmerStory,
        ], 200);
    }

    /** PUT /api/farmer-stories/{id} ? Update a farmer story */
    public function update(Request $request, FarmerStory $farmerStory)
    {
        $validator = Validator::make($request->all(), [
            'farmer_name'       => 'sometimes|required|string|max:150',
            'mobile_number'     => 'sometimes|required|string|max:15',
            'suggested_product' => 'sometimes|required|string|max:255',
            'location'          => 'nullable|string|max:255',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'is_active'         => 'boolean',
        ]);

        if (
$validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => 
$validator->errors(),
            ], 422);
        }

        
$data = $validator->validated();

        if (
$request->hasFile('image')) {
            if (
$farmerStory->image && file_exists(public_path($farmerStory->image))) {
                unlink(public_path(
$farmerStory->image));
            }
            
$file      = $request->file('image');
            
$imageName = time() . '_' . $file->getClientOriginalName();
            
$file->move(public_path('assets/images/farmer-stories'), $imageName);
            
$data['image'] = 'assets/images/farmer-stories/' . $imageName;
        }

        
$farmerStory->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Farmer story updated successfully.',
            'data'    => 
$farmerStory->fresh(),
        ], 200);
    }

    /** DELETE /api/farmer-stories/{id} ? Delete a farmer story */
    public function destroy(FarmerStory $farmerStory)
    {
        if (
$farmerStory->image && file_exists(public_path($farmerStory->image))) {
            unlink(public_path(
$farmerStory->image));
        }
        
$farmerStory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Farmer story deleted successfully.',
        ], 200);
    }
}
