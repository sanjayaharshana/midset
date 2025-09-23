# Settings Helper Usage Guide

## Overview

The Settings Helper provides a comprehensive and dynamic way to manage application settings with caching, validation, and easy-to-use helper functions.

## Features

- ✅ **Dynamic Settings Creation** - Create settings on-the-fly
- ✅ **Caching** - Automatic caching for performance
- ✅ **Validation** - Type-based validation
- ✅ **Search** - Search settings by key, label, description, or value
- ✅ **Import/Export** - Bulk import/export functionality
- ✅ **Grouping** - Organize settings by groups
- ✅ **Helper Functions** - Easy-to-use global functions
- ✅ **API Endpoints** - RESTful API for settings management

## Helper Functions

### Basic Usage

```php
// Get a setting value
$companyName = setting('company_name');

// Set a setting value
setting('company_name', 'My Company');

// Set with options
setting('new_setting', 'value', [
    'type' => 'text',
    'group' => 'general',
    'label' => 'New Setting',
    'description' => 'Description of the setting',
    'is_public' => true
]);

// Get multiple settings
$settings = settings(['company_name', 'app_name', 'email_from']);

// Check if setting exists
if (setting_exists('company_name')) {
    // Setting exists
}

// Delete a setting
setting_delete('unwanted_setting');

// Clear cache
setting_clear_cache();
```

### Advanced Usage

```php
use App\Helpers\SettingsHelper;

// Get settings by group
$companySettings = SettingsHelper::getCompanySettings();
$systemSettings = SettingsHelper::getSystemSettings();
$emailSettings = SettingsHelper::getEmailSettings();

// Get all settings grouped
$allSettings = SettingsHelper::getGrouped();

// Search settings
$results = SettingsHelper::search('company', ['company', 'system']);

// Get settings with pagination
$paginatedSettings = SettingsHelper::getPaginated(15, [
    'group' => 'company',
    'type' => 'text'
]);

// Export settings
$exportedSettings = SettingsHelper::export(['company', 'system']);

// Import settings
$importResults = SettingsHelper::import($settingsArray);

// Get settings count by group
$counts = SettingsHelper::getCountByGroup();
// Returns: ['company' => 6, 'system' => 5, 'email' => 3]

// Validate setting value
$isValid = SettingsHelper::validateValue('test@example.com', 'email');

// Get typed value (with proper casting)
$booleanValue = SettingsHelper::getTyped('feature_enabled', false);
```

## API Endpoints

### Get Settings
```
GET /admin/settings/group/{group}
GET /admin/settings/get/{key}
```

### Create Setting
```
POST /admin/settings/create
Content-Type: application/json

{
    "key": "new_setting",
    "value": "setting value",
    "type": "text",
    "group": "general",
    "label": "New Setting",
    "description": "Description",
    "is_public": false
}
```

### Delete Setting
```
DELETE /admin/settings/{key}
```

### Search Settings
```
GET /admin/settings/search?q=company&groups[]=company&groups[]=system
```

### Export Settings
```
GET /admin/settings/export?groups[]=company&groups[]=system
```

### Import Settings
```
POST /admin/settings/import
Content-Type: application/json

{
    "settings": [
        {
            "key": "setting1",
            "value": "value1",
            "type": "text",
            "group": "general",
            "label": "Setting 1"
        }
    ]
}
```

## Setting Types

- **text** - Plain text input
- **textarea** - Multi-line text input
- **number** - Numeric input
- **email** - Email input with validation
- **url** - URL input with validation
- **boolean** - Yes/No dropdown

## Setting Groups

- **company** - Company information
- **system** - System configuration
- **email** - Email settings
- **notifications** - Notification preferences
- **general** - General settings

## Examples

### Creating Dynamic Settings

```php
// Create a new setting dynamically
$setting = SettingsHelper::create([
    'key' => 'maintenance_mode',
    'value' => '0',
    'type' => 'boolean',
    'group' => 'system',
    'label' => 'Maintenance Mode',
    'description' => 'Enable maintenance mode for the application',
    'is_public' => false
]);
```

### Using in Controllers

```php
class HomeController extends Controller
{
    public function index()
    {
        $companyName = setting('company_name');
        $appName = setting('app_name');
        $maintenanceMode = SettingsHelper::getTyped('maintenance_mode', false);
        
        if ($maintenanceMode) {
            return view('maintenance');
        }
        
        return view('home', compact('companyName', 'appName'));
    }
}
```

### Using in Blade Templates

```blade
{{-- Get setting value --}}
<h1>{{ setting('company_name') }}</h1>

{{-- Check if setting exists --}}
@if(setting_exists('feature_enabled'))
    <div class="feature">
        Feature is enabled
    </div>
@endif

{{-- Get multiple settings --}}
@php
    $settings = settings(['company_name', 'app_name', 'email_from']);
@endphp

<p>Welcome to {{ $settings['app_name'] }} by {{ $settings['company_name'] }}</p>
```

### Using in Middleware

```php
class MaintenanceMiddleware
{
    public function handle($request, Closure $next)
    {
        if (SettingsHelper::getTyped('maintenance_mode', false)) {
            return response()->view('maintenance', [], 503);
        }
        
        return $next($request);
    }
}
```

## Caching

Settings are automatically cached for 1 hour (3600 seconds). The cache is cleared when:
- A setting is updated
- A setting is deleted
- `setting_clear_cache()` is called

## Validation

The helper includes built-in validation for different setting types:

```php
// Email validation
$isValid = SettingsHelper::validateValue('test@example.com', 'email');

// URL validation
$isValid = SettingsHelper::validateValue('https://example.com', 'url');

// Number validation
$isValid = SettingsHelper::validateValue('123', 'number');

// Boolean validation
$isValid = SettingsHelper::validateValue('1', 'boolean');
```

## Performance Tips

1. **Use helper functions** for simple get/set operations
2. **Use SettingsHelper class** for complex operations
3. **Cache is automatic** - no need to manually manage
4. **Use getMultiple()** instead of multiple get() calls
5. **Use getByGroup()** for group-specific operations

## Error Handling

```php
try {
    $setting = SettingsHelper::create($data);
} catch (\InvalidArgumentException $e) {
    // Handle validation errors
    Log::error('Setting creation failed: ' . $e->getMessage());
} catch (\Exception $e) {
    // Handle other errors
    Log::error('Unexpected error: ' . $e->getMessage());
}
```

## Best Practices

1. **Use descriptive keys** - `company_name` instead of `cn`
2. **Group related settings** - Use consistent group names
3. **Provide descriptions** - Help users understand the setting
4. **Use appropriate types** - Choose the right input type
5. **Set is_public carefully** - Only expose necessary settings
6. **Use validation** - Validate values before setting
7. **Handle errors gracefully** - Always check for errors

## Migration from Old Settings

If you're migrating from a different settings system:

```php
// Old way
$value = config('app.company_name');

// New way
$value = setting('company_name');
```

The helper automatically creates settings with default options if they don't exist, making migration seamless.
