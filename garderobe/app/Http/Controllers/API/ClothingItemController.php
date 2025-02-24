<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ClothingItem;
use Illuminate\Http\Request;

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

        return $query->with('category')->paginate(10);
    }

    public function store(Request $request)
    {
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

        $validatedData['user_id'] = auth()->id();
        return ClothingItem::create($validatedData);
    }

    public function update(Request $request, ClothingItem $clothingItem)
    {
        $this->authorize('update', $clothingItem);

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
        return $clothingItem;
    }

    public function destroy(ClothingItem $clothingItem)
    {
        $this->authorize('delete', $clothingItem);
        $clothingItem->delete();
        return response()->json(null, 204);
    }
}