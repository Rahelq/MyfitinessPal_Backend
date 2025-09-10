<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExerciseCatagories;

class AdminExerciseCategoryController extends Controller
{
    // List all categories
    public function index()
    {
        return response()->json(ExerciseCatagories::all());
    }

    // Create new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:exercise_catagories,name',
            'description' => 'nullable|string',
        ]);

        $category = ExerciseCatagories::create($request->all());

        return response()->json(['message' => 'Exercise category created', 'category' => $category]);
    }

    // Update category
    public function update(Request $request, $id)
    {
        $category = ExerciseCatagories::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:exercise_catagories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return response()->json(['message' => 'Exercise category updated', 'category' => $category]);
    }

    // Delete category
    public function destroy($id)
    {
        $category = ExerciseCatagories::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Exercise category deleted']);
    }
}
