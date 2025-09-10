<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminSettingsController extends Controller
{
    // View all settings
    public function index()
    {
        $settings = DB::table('system_settings')->get();
        return response()->json($settings);
    }

    // Update a specific setting by key
    public function update(Request $request, $key)
    {
        $validated = $request->validate([
            'value' => 'required|string'
        ]);

        $updated = DB::table('system_settings')
            ->where('key', $key)
            ->update(['value' => $validated['value']]);

        if ($updated) {
            return response()->json(['message' => "Setting '$key' updated successfully."]);
        } else {
            return response()->json(['message' => "Setting '$key' not found."], 404);
        }
    }
}
