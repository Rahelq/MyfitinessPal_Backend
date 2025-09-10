<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FoodItem;
use App\Models\FoodDiaryEntry;
use App\Models\CheckIn;
use App\Models\ExerciseDatabase;
use App\Models\CardioExerciseEntries;
use App\Models\StrengthExerciseEntries;
use App\Models\QuickExerciseEntries;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    // 1️⃣ User engagement & stats
    public function users()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();

        return response()->json([
            'total_users' => $totalUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $inactiveUsers,
        ]);
    }

    // 2️⃣ Most popular foods
    public function exercises()
    {
        // Get popular strength exercises
        $strengthExercises = StrengthExerciseEntries::select('exercise_id', DB::raw('COUNT(*) as performed_count'))
            ->groupBy('exercise_id');

        // Get popular cardio exercises and union with strength exercises
        $popularExercises = CardioExerciseEntries::select('exercise_id', DB::raw('COUNT(*) as performed_count'))
            ->groupBy('exercise_id')
            ->union($strengthExercises)
            ->get()
            ->sortByDesc('performed_count')
            ->take(10);

        // Map the results to include exercise name
        $exerciseIds = $popularExercises->pluck('exercise_id');
        $exerciseNames = ExerciseDatabase::whereIn('exercise_id', $exerciseIds)
            ->pluck('exercise_name', 'exercise_id');

        $popularExercises = $popularExercises->map(function ($exercise) use ($exerciseNames) {
            return [
                'exercise_id' => $exercise->exercise_id,
                'performed_count' => $exercise->performed_count,
                'exercise_name' => $exerciseNames[$exercise->exercise_id] ?? 'N/A'
            ];
        });

        return response()->json($popularExercises);
    }

    // 3️⃣ Most popular food
    public function food()
    {
        $popularExercises = ExerciseDashboard::select('exercise_id', DB::raw('COUNT(*) as performed_count'))
            ->groupBy('exercise_id')
            ->with('exercise:id,name') // eager load exercise name
            ->orderByDesc('performed_count')
            ->take(10)
            ->get();

        return response()->json($popularExercises);
    }

    // 4️⃣ System usage (login attempts / active sessions)
    public function systemUsage()
    {
        // Example: you can track login attempts in your `login_attempts` table
        $totalLogins = DB::table('login_attempts')->count();
        $failedLogins = DB::table('login_attempts')->where('status', 'failed')->count();
        $activeSessions = DB::table('user_sessions')->count();

        return response()->json([
            'total_logins' => $totalLogins,
            'failed_logins' => $failedLogins,
            'active_sessions' => $activeSessions,
        ]);
    }

    // 5️⃣ User progress: weight & activity summary
    public function userProgress($user_id)
    {
        $user = User::findOrFail($user_id);

        $weightTrend = CheckIn::where('user_id', $user_id)
            ->orderBy('date')
            ->get(['date', 'weight_kg']);

        // Sum cardio and strength exercise calories
        $cardioCalories = CardioExerciseEntries::where('user_id', $user_id)->sum('calories_burned');
        $strengthCalories = StrengthExerciseEntries::where('user_id', $user_id)->sum('calories_burned');
        $quickCalories = QuickExerciseEntries::where('user_id', $user_id)->sum('calories_burned');

        $exerciseSummary = [
            'total_calories_burned' => $cardioCalories + $strengthCalories + $quickCalories,
        ];

        $foodIntakeSummary = FoodDiaryEntry::where('user_id', $user_id)
            ->select(DB::raw('SUM(calories_consumed) as total_calories'), DB::raw('DATE(entry_date) as date'))
            ->groupBy('date')
            ->get();

        return response()->json([
            'user' => $user->only('id', 'first_name', 'last_name', 'email'),
            'weight_trend' => $weightTrend,
            'exercise_summary' => $exerciseSummary,
            'food_intake_summary' => $foodIntakeSummary,
        ]);
    }
}
