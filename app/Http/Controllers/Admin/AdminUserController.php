<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGoal;
use App\Models\CheckIn;
use Throwable;

class AdminUserController extends Controller
{
    // List all users
    public function index()
    {
        try {
            $users = User::all(['id', 'first_name', 'last_name', 'email', 'role', 'is_active', 'created_at']);
            return response()->json(['message' => 'success', 'data' => $users], 200);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // Show single user details
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['message'=>'success','data'=>$user],200);
        } catch (Throwable $e) {
            return response()->json(['message'=>'Error: '.$e->getMessage()],404);
        }
    }

    // Update user info (role, is_active, etc.)
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'role' => 'sometimes|in:admin,user,moderator',
                'is_active' => 'sometimes|boolean',
            ]);

            $user->update($validated);

            return response()->json(['message' => 'User updated successfully', 'data' => $user], 200);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    // Soft delete / deactivate user
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_active = false; // soft deactivate
            $user->save();

            return response()->json(['message' => 'User deactivated successfully'], 200);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    // List goals for a user
    public function goals($id)
    {
        try {
            $goals = UserGoal::where('user_id', $id)->get();
            return response()->json(['message' => 'success', 'data' => $goals], 200);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 404);
        }
    }

    // List weight check-ins for a user
    public function checkins($id)
    {
        try {
            $checkins = CheckIn::where('user_id', $id)->orderBy('date', 'desc')->get();
            return response()->json(['message' => 'success', 'data' => $checkins], 200);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 404);
        }
    }

    // Create a new user
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed', // password_confirmation required in request
                'role' => 'required|in:admin,user,moderator',
            ]);

            $validated['password'] = bcrypt($validated['password']);

            $user = User::create($validated);

            return response()->json(['message' => 'User created successfully', 'data' => $user], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // Activate / Deactivate user
    public function toggleStatus($userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Toggle the is_active boolean field
            $user->is_active = !$user->is_active;
            $user->save();

            return response()->json([
                'message' => "User status updated successfully",
                'user' => $user
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
