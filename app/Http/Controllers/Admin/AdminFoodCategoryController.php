<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FoodCategory;

class AdminFoodCategoryController extends Controller
{
    // List all categories
    public function index()
    {
        return response()->json(FoodCategory::all());
    }

    // Create new category
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|unique:food_categories,category_name',
        ]);

        $category = FoodCategory::create([
            'category_name' => $request->input('category_name')
        ]);

        return response()->json(['message' => 'Category created', 'category' => $category]);
    }

    // Update category
    public function update(Request $request, $id)
    {
        $category = FoodCategory::findOrFail($id);
        $request->validate([
            'category_name' => 'required|string|unique:food_categories,category_name,' . $id . ',category_id',
        ]);

        $category->update([
            'category_name' => $request->input('category_name')
        ]);

        return response()->json(['message' => 'Category updated', 'category' => $category]);
    }

    // Delete category
    public function destroy($id)
    {
        $category = FoodCategory::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted']);
    }
}
