<?php

if (!function_exists('setting')) {
    /**
     * Get or set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param array $options
     * @return mixed
     */
    function setting($key, $value = null, array $options = [])
    {
        if ($value !== null && !is_array($options) && empty($options)) {
            // If setting doesn't exist, create it with default options
            if (!\App\Helpers\SettingsHelper::exists($key)) {
                $defaultOptions = array_merge([
                    'type' => 'text',
                    'group' => 'general',
                    'label' => ucfirst(str_replace('_', ' ', $key)),
                    'description' => null,
                    'is_public' => false,
                ], $options);
                
                return \App\Helpers\SettingsHelper::set($key, $value, $defaultOptions);
            }
            
            return \App\Helpers\SettingsHelper::set($key, $value, $options);
        }
        
        return \App\Helpers\SettingsHelper::get($key, $value);
    }
}

if (!function_exists('get_setting')) {
    /**
     * Get a setting value with default fallback
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_setting($key, $default = null)
    {
        return \App\Helpers\SettingsHelper::get($key, $default);
    }
}

if (!function_exists('settings')) {
    /**
     * Get multiple settings or all settings
     *
     * @param array $keys
     * @return array
     */
    function settings(array $keys = [])
    {
        if (empty($keys)) {
            return \App\Helpers\SettingsHelper::getAsArray();
        }
        
        return \App\Helpers\SettingsHelper::getMultiple($keys);
    }
}

if (!function_exists('setting_exists')) {
    /**
     * Check if a setting exists
     *
     * @param string $key
     * @return bool
     */
    function setting_exists(string $key): bool
    {
        return \App\Helpers\SettingsHelper::exists($key);
    }
}

if (!function_exists('setting_delete')) {
    /**
     * Delete a setting
     *
     * @param string $key
     * @return bool
     */
    function setting_delete(string $key): bool
    {
        return \App\Helpers\SettingsHelper::delete($key);
    }
}

if (!function_exists('setting_clear_cache')) {
    /**
     * Clear all settings cache
     *
     * @return void
     */
    function setting_clear_cache(): void
    {
        \App\Helpers\SettingsHelper::clearCache();
    }
}
