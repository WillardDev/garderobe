<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ClothingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClothingItemController extends Controller
{
    public function index(Request $request)
    {
        $query = ClothingItem::where('user_id', auth()->id());

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Get the per_page parameter from request, default to 12 if not provided
        $perPage = $request->input('per_page', 12);
        
        // Set a reasonable maximum to prevent performance issues
        $perPage = min($perPage, 500);

        return $query->with('category')->paginate($perPage);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // Find the item by ID
            $clothingItem = ClothingItem::find($id);
            
            // Check if item exists
            if (!$clothingItem) {
                return response()->json(['message' => 'Item not found'], 404);
            }
            
            // Check if the authenticated user owns this item
            if ($clothingItem->user_id !== auth()->id()) {
                return response()->json(['message' => 'You do not have permission to view this item'], 403);
            }
            
            return response()->json($clothingItem);
        } catch (\Exception $e) {
            Log::error('Error showing clothing item', [
                'error' => $e->getMessage(),
                'item_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'An error occurred while retrieving the item'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|max:255',
                'description' => 'nullable|max:1000',
                'color' => 'required|max:50',
                'size' => 'required|max:20',
                'brand' => 'nullable|max:100',
                'image' => 'nullable|image|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images', 'public');
                $validatedData['image_path'] = $path;
            }

            $validatedData['user_id'] = auth()->id();
            $item = ClothingItem::create($validatedData);
            
            return response()->json($item, 201);
        } catch (\Exception $e) {
            Log::error('Error creating clothing item', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'An error occurred while creating the item'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the item by ID
            $clothingItem = ClothingItem::find($id);
            
            // Check if item exists
            if (!$clothingItem) {
                return response()->json(['message' => 'Item not found'], 404);
            }
            
            // Check if the authenticated user owns this item
            if ($clothingItem->user_id !== auth()->id()) {
                return response()->json(['message' => 'You do not have permission to update this item'], 403);
            }

            $validatedData = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|max:255',
                'description' => 'nullable|max:1000',
                'color' => 'required|max:50',
                'size' => 'required|max:20',
                'brand' => 'nullable|max:100',
                'image' => 'nullable|image|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('clothing_images', 'public');
                $validatedData['image_path'] = $path;
            }

            $clothingItem->update($validatedData);
            return response()->json($clothingItem);
        } catch (\Exception $e) {
            Log::error('Error updating clothing item', [
                'error' => $e->getMessage(),
                'item_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'An error occurred while updating the item'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Find the item by ID
            $clothingItem = ClothingItem::find($id);
            
            // Check if item exists
            if (!$clothingItem) {
                return response()->json(['message' => 'Item not found'], 404);
            }
            
            // Check if the authenticated user owns this item
            if ($clothingItem->user_id !== auth()->id()) {
                return response()->json(['message' => 'You do not have permission to delete this item'], 403);
            }
            
            $clothingItem->delete();
            return response()->json(['message' => 'Item deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting clothing item', [
                'error' => $e->getMessage(),
                'item_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            return response()->json(['message' => 'An error occurred while deleting the item'], 500);
        }
    }
}
