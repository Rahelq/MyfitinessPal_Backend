<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExerciseDatabase;

class AdminExerciseController extends Controller
{
    // List all exercises
    public function index()
    {
        return response()->json(ExerciseDatabase::all());
    }

    // Exercise details
    public function show($id)
    {
        $exercise = ExerciseDatabase::findOrFail($id);
        return response()->json($exercise);
    }

    // Add new exercise
    public function store(Request $request)
    {
        $request->validate([
            'exercise_name' => 'required|string|unique:exercise_databases,exercise_name',
            'catagory_id' => 'required|exists:exercise_catagories,id',
            'exercise_type' => 'required|string|in:cardio,strength',
            'calories_per_minute' => 'required|numeric',
        ]);
        
        $data = $request->only([
            'exercise_name',
            'catagory_id',
            'exercise_type',
            'calories_per_minute'
        ]);

        // New exercises are not verified or public by default.
        $data['is_verified'] = false;
        $data['is_public'] = false;

        $exercise = ExerciseDatabase::create($data);

        return response()->json(['message' => 'Exercise added', 'exercise' => $exercise]);
    }

    // Update exercise
    public function update(Request $request, $id)
    {
        $exercise = ExerciseDatabase::findOrFail($id);

        $request->validate([
            'exercise_name' => 'required|string|unique:exercise_databases,exercise_name,' . $id . ',exercise_id',
            'catagory_id' => 'required|exists:exercise_catagories,id',
            'exercise_type' => 'required|string|in:cardio,strength',
            'calories_per_minute' => 'required|numeric',
        ]);

        $exercise->update($request->all());

        return response()->json(['message' => 'Exercise updated', 'exercise' => $exercise]);
    }

    // Delete exercise
    public function destroy($id)
    {
        $exercise = ExerciseDatabase::findOrFail($id);
        $exercise->delete();

        return response()->json(['message' => 'Exercise deleted']);
    }

    // Pending exercises (user-submitted)
    public function pending()
    {
         $pendingExercises = ExerciseDatabase::where('is_public', false)->where('is_verified', false)->get();
        return response()->json($pendingExercises);
    }

    // Approve exercise
    public function approve($id)
    {
        $exercise = ExerciseDatabase::findOrFail($id);
        $exercise->is_public = true;
        $exercise->is_verified = true;
        $exercise->save();

        return response()->json(['message' => 'Exercise approved', 'exercise' => $exercise]);
    }

    // Reject exercise
    public function reject($id)
    {
        $exercise = ExerciseDatabase::findOrFail($id);
        $exercise->is_public = false;
        $exercise->is_verified = false;
        $exercise->save();

        return response()->json(['message' => 'Exercise rejected', 'exercise' => $exercise]);
    }
}
