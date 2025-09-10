<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodItem;

class AdminFoodItemController extends Controller
{
    // List all items
    public function index()
    {
        return response()->json(FoodItem::all());
    }

    // Food item details
    public function show($id)
    {
        $item = FoodItem::findOrFail($id);
        return response()->json($item);
    }

    // Add new item
    public function store(Request $request)
    {
        $request->validate([
            'food_name' => 'required|string|unique:food_items,food_name',
            'category_id' => 'required|exists:food_categories,category_id',
            'calories_per_serving' => 'required|numeric',
            'protein_per_serving' => 'nullable|numeric',
            'carbs_per_serving' => 'nullable|numeric',
            'fat_per_serving' => 'nullable|numeric',
            'fiber_per_serving' => 'nullable|numeric',
        ]);

        $data = $request->only([
            'food_name',
            'category_id',
            'calories_per_serving',
            'protein_per_serving',
            'carbs_per_serving',
            'fat_per_serving',
            'fiber_per_serving'
        ]);

        // New items are not verified or public by default.
        $data['is_verified'] = false;
        $data['is_public'] = false;

        $item = FoodItem::create($data);

        return response()->json(['message' => 'Food item added', 'item' => $item]);
    }

    // Update item
    public function update(Request $request, $id)
    {
        $item = FoodItem::findOrFail($id);

        $request->validate([
            'food_name' => 'required|string|unique:food_items,food_name,' . $id . ',food_id',
            'category_id' => 'required|exists:food_categories,category_id',
            'calories_per_serving' => 'required|numeric',
            'protein_per_serving' => 'nullable|numeric',
            'carbs_per_serving' => 'nullable|numeric',
            'fat_per_serving' => 'nullable|numeric',
            'fiber_per_serving' => 'nullable|numeric',
        ]);

        $item->update($request->all());

        return response()->json(['message' => 'Food item updated', 'item' => $item]);
    }

    // Delete item
    public function destroy($id)
    {
        $item = FoodItem::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Food item deleted']);
    }

    // Pending items (user-submitted)
    public function pending()
    {
        $pendingItems = FoodItem::where('is_public', false)->where('is_verified', false)->get();
        return response()->json($pendingItems);
    }

    // Approve item
    public function approve($id)
    {
        $item = FoodItem::findOrFail($id);
        $item->is_public = true;
        $item->is_verified = true;
        $item->save();

        return response()->json(['message' => 'Food item approved', 'item' => $item]);
    }

    // Reject item
    public function reject($id)
    {
        $item = FoodItem::findOrFail($id);
        $item->is_public = false;
        $item->is_verified = false;
        $item->save();

        return response()->json(['message' => 'Food item rejected', 'item' => $item]);
    }
}
