<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FoodItem;
use App\Models\ExerciseDatabase;
use App\Models\CardioExerciseEntries;
use App\Models\StrengthExerciseEntries;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total users
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();

        // Food stats
        $totalFoodItems = FoodItem::count();
        $pendingFoodItems = FoodItem::where('is_verified', false)->orWhere('is_public', false)->count();

        // Exercise stats
        $totalExercises = ExerciseDatabase::count();
        $pendingExercises = ExerciseDatabase::where('is_verified', false)->orWhere('is_public', false)->count();

        // Activity stats
        $totalCardioEntries = CardioExerciseEntries::count();
        $totalStrengthEntries = StrengthExerciseEntries::count();

        // Most popular foods (top 5 by usage)
        $popularFoods = FoodItem::select('name', DB::raw('COUNT(*) as usage_count'))
            ->groupBy('name')
            ->orderByDesc('usage_count')
            ->limit(5)
            ->get();

        // Most performed exercises (top 5)
        $popularExercises = ExerciseDatabase::select('exercise_name', DB::raw('COUNT(*) as usage_count'))
            ->join('cardio_exercise_entries', 'exercise_databases.exercise_id', '=', 'cardio_exercise_entries.exercise_id')
            ->groupBy('exercise_name')
            ->orderByDesc('usage_count')
            ->limit(5)
            ->get();

        // Recent registered users
        $recentUsers = User::orderByDesc('created_at')->limit(5)->get(['id','first_name','last_name','email','is_active','created_at']);

        return response()->json([
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
            'total_food_items' => $totalFoodItems,
            'pending_food_items' => $pendingFoodItems,
            'total_exercises' => $totalExercises,
            'pending_exercises' => $pendingExercises,
            'total_cardio_entries' => $totalCardioEntries,
            'total_strength_entries' => $totalStrengthEntries,
            'popular_foods' => $popularFoods,
            'popular_exercises' => $popularExercises,
            'recent_users' => $recentUsers
        ]);
    }
}
