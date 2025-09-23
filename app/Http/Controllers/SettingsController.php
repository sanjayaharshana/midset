<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view settings')->only(['index']);
        $this->middleware('permission:edit settings')->only(['update']);
    }

    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = SettingsHelper::getGrouped();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            SettingsHelper::setMultiple($request->settings);

            return redirect()->route('admin.settings.index')
                ->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update settings: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Get settings by group (API endpoint)
     */
    public function getByGroup($group)
    {
        $settings = SettingsHelper::getByGroup($group);
        return response()->json($settings);
    }

    /**
     * Get a specific setting value (API endpoint)
     */
    public function get($key)
    {
        $value = SettingsHelper::get($key);
        return response()->json(['key' => $key, 'value' => $value]);
    }

    /**
     * Create a new setting dynamically
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255|unique:settings',
            'value' => 'nullable|string',
            'type' => 'required|in:text,textarea,number,email,url,boolean',
            'group' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $setting = SettingsHelper::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Setting created successfully',
                'setting' => $setting
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create setting: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a setting
     */
    public function destroy($key)
    {
        try {
            $deleted = SettingsHelper::delete($key);
            
            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Setting deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete setting: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search settings
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $groups = $request->get('groups', []);
        
        if (empty($query)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required'
            ], 400);
        }

        try {
            $settings = SettingsHelper::search($query, $groups);
            
            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export settings
     */
    public function export(Request $request)
    {
        $groups = $request->get('groups', []);
        
        try {
            $settings = SettingsHelper::export($groups);
            
            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import settings
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string|max:255',
            'settings.*.value' => 'nullable|string',
            'settings.*.type' => 'required|in:text,textarea,number,email,url,boolean',
            'settings.*.group' => 'required|string|max:255',
            'settings.*.label' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $results = SettingsHelper::import($request->settings);
            
            return response()->json([
                'success' => true,
                'message' => 'Settings imported successfully',
                'results' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }
}