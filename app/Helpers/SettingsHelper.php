<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SettingsHelper
{
    /**
     * Get a setting value by key with fallback
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "setting.{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value, array $options = [])
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            array_merge([
                'value' => $value,
            ], $options)
        );

        // Clear cache
        Cache::forget("setting.{$key}");

        return $setting;
    }

    /**
     * Get multiple settings at once
     */
    public static function getMultiple(array $keys, array $defaults = [])
    {
        $settings = [];
        
        foreach ($keys as $key) {
            $default = $defaults[$key] ?? null;
            $settings[$key] = self::get($key, $default);
        }
        
        return $settings;
    }

    /**
     * Set multiple settings at once
     */
    public static function setMultiple(array $settings, array $options = [])
    {
        $results = [];
        
        foreach ($settings as $key => $value) {
            $results[$key] = self::set($key, $value, $options);
        }
        
        return $results;
    }

    /**
     * Get all settings grouped by group
     */
    public static function getGrouped()
    {
        return Setting::orderBy('group')->orderBy('label')->get()->groupBy('group');
    }

    /**
     * Get settings by group
     */
    public static function getByGroup(string $group)
    {
        return Setting::where('group', $group)->orderBy('label')->get();
    }

    /**
     * Get settings as key-value array
     */
    public static function getAsArray(array $keys = [])
    {
        $query = Setting::query();
        
        if (!empty($keys)) {
            $query->whereIn('key', $keys);
        }
        
        return $query->pluck('value', 'key')->toArray();
    }

    /**
     * Check if a setting exists
     */
    public static function exists(string $key): bool
    {
        return Setting::where('key', $key)->exists();
    }

    /**
     * Delete a setting
     */
    public static function delete(string $key): bool
    {
        $deleted = Setting::where('key', $key)->delete();
        
        if ($deleted) {
            Cache::forget("setting.{$key}");
        }
        
        return $deleted > 0;
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        $keys = Setting::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting.{$key}");
        }
    }

    /**
     * Create a new setting dynamically
     */
    public static function create(array $data)
    {
        $validator = Validator::make($data, [
            'key' => 'required|string|max:255|unique:settings',
            'value' => 'nullable|string',
            'type' => 'required|in:text,textarea,number,email,url,boolean',
            'group' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Invalid setting data: ' . implode(', ', $validator->errors()->all()));
        }

        $setting = Setting::create($data);
        
        // Clear cache for this key
        Cache::forget("setting.{$setting->key}");
        
        return $setting;
    }

    /**
     * Update setting metadata (not value)
     */
    public static function updateMetadata(string $key, array $metadata)
    {
        $setting = Setting::where('key', $key)->first();
        
        if (!$setting) {
            throw new \InvalidArgumentException("Setting with key '{$key}' not found");
        }

        $setting->update($metadata);
        
        return $setting;
    }

    /**
     * Get company settings
     */
    public static function getCompanySettings()
    {
        return self::getByGroup('company');
    }

    /**
     * Get system settings
     */
    public static function getSystemSettings()
    {
        return self::getByGroup('system');
    }

    /**
     * Get email settings
     */
    public static function getEmailSettings()
    {
        return self::getByGroup('email');
    }

    /**
     * Get notification settings
     */
    public static function getNotificationSettings()
    {
        return self::getByGroup('notifications');
    }

    /**
     * Get public settings (for frontend use)
     */
    public static function getPublicSettings()
    {
        return Setting::where('is_public', true)
            ->orderBy('group')
            ->orderBy('label')
            ->get()
            ->groupBy('group');
    }

    /**
     * Get setting with type casting
     */
    public static function getTyped(string $key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Cast value based on type
     */
    private static function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'number':
                return is_numeric($value) ? (float) $value : 0;
            case 'email':
            case 'url':
            case 'text':
            case 'textarea':
            default:
                return (string) $value;
        }
    }

    /**
     * Validate setting value based on type
     */
    public static function validateValue($value, string $type): bool
    {
        switch ($type) {
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            case 'url':
                return filter_var($value, FILTER_VALIDATE_URL) !== false;
            case 'number':
                return is_numeric($value);
            case 'boolean':
                return in_array($value, ['0', '1', 'true', 'false', true, false], true);
            default:
                return true;
        }
    }

    /**
     * Get settings for a specific user role
     */
    public static function getForRole(string $role)
    {
        // This can be extended to have role-specific settings
        return Setting::where('is_public', true)->get();
    }

    /**
     * Export settings to array
     */
    public static function export(array $groups = []): array
    {
        $query = Setting::query();
        
        if (!empty($groups)) {
            $query->whereIn('group', $groups);
        }
        
        return $query->get()->toArray();
    }

    /**
     * Import settings from array
     */
    public static function import(array $settings): array
    {
        $results = [];
        
        foreach ($settings as $settingData) {
            try {
                $setting = Setting::updateOrCreate(
                    ['key' => $settingData['key']],
                    $settingData
                );
                $results[] = $setting;
                
                // Clear cache
                Cache::forget("setting.{$setting->key}");
            } catch (\Exception $e) {
                $results[] = ['error' => $e->getMessage(), 'data' => $settingData];
            }
        }
        
        return $results;
    }

    /**
     * Get settings count by group
     */
    public static function getCountByGroup(): array
    {
        return Setting::selectRaw('`group`, COUNT(*) as count')
            ->groupBy('group')
            ->pluck('count', 'group')
            ->toArray();
    }

    /**
     * Search settings
     */
    public static function search(string $query, array $groups = [])
    {
        $searchQuery = Setting::where(function ($q) use ($query) {
            $q->where('key', 'like', "%{$query}%")
              ->orWhere('label', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhere('value', 'like', "%{$query}%");
        });
        
        if (!empty($groups)) {
            $searchQuery->whereIn('group', $groups);
        }
        
        return $searchQuery->get();
    }

    /**
     * Get settings with pagination
     */
    public static function getPaginated(int $perPage = 15, array $filters = [])
    {
        $query = Setting::query();
        
        if (isset($filters['group'])) {
            $query->where('group', $filters['group']);
        }
        
        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        if (isset($filters['is_public'])) {
            $query->where('is_public', $filters['is_public']);
        }
        
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('key', 'like', "%{$filters['search']}%")
                  ->orWhere('label', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }
        
        return $query->orderBy('group')->orderBy('label')->paginate($perPage);
    }
}
