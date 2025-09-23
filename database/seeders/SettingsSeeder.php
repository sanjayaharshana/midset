<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Company Settings
            [
                'key' => 'company_name',
                'value' => 'Mindpark Solutions',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Name',
                'description' => 'The official name of your company',
                'is_public' => true,
            ],
            [
                'key' => 'company_address',
                'value' => '123 Business Street, City, State 12345',
                'type' => 'textarea',
                'group' => 'company',
                'label' => 'Company Address',
                'description' => 'Complete business address',
                'is_public' => true,
            ],
            [
                'key' => 'company_phone',
                'value' => '+1 (555) 123-4567',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Phone',
                'description' => 'Main business phone number',
                'is_public' => true,
            ],
            [
                'key' => 'company_email',
                'value' => 'info@mindpark.com',
                'type' => 'email',
                'group' => 'company',
                'label' => 'Company Email',
                'description' => 'Main business email address',
                'is_public' => true,
            ],
            [
                'key' => 'company_website',
                'value' => 'https://www.mindpark.com',
                'type' => 'url',
                'group' => 'company',
                'label' => 'Company Website',
                'description' => 'Official company website URL',
                'is_public' => true,
            ],
            [
                'key' => 'company_logo',
                'value' => '',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Company Logo URL',
                'description' => 'URL to the company logo image',
                'is_public' => true,
            ],

            // System Settings
            [
                'key' => 'app_name',
                'value' => 'Mindpark Admin',
                'type' => 'text',
                'group' => 'system',
                'label' => 'Application Name',
                'description' => 'Name displayed in the application header',
                'is_public' => false,
            ],
            [
                'key' => 'app_description',
                'value' => 'Comprehensive business management system',
                'type' => 'textarea',
                'group' => 'system',
                'label' => 'Application Description',
                'description' => 'Brief description of the application',
                'is_public' => false,
            ],
            [
                'key' => 'timezone',
                'value' => 'UTC',
                'type' => 'text',
                'group' => 'system',
                'label' => 'Default Timezone',
                'description' => 'Default timezone for the application',
                'is_public' => false,
            ],
            [
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'type' => 'text',
                'group' => 'system',
                'label' => 'Date Format',
                'description' => 'Default date format (PHP date format)',
                'is_public' => false,
            ],
            [
                'key' => 'items_per_page',
                'value' => '10',
                'type' => 'number',
                'group' => 'system',
                'label' => 'Items Per Page',
                'description' => 'Default number of items to display per page',
                'is_public' => false,
            ],

            // Email Settings
            [
                'key' => 'mail_from_name',
                'value' => 'Mindpark Solutions',
                'type' => 'text',
                'group' => 'email',
                'label' => 'Email From Name',
                'description' => 'Name used in outgoing emails',
                'is_public' => false,
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@mindpark.com',
                'type' => 'email',
                'group' => 'email',
                'label' => 'Email From Address',
                'description' => 'Email address used for outgoing emails',
                'is_public' => false,
            ],
            [
                'key' => 'mail_reply_to',
                'value' => 'support@mindpark.com',
                'type' => 'email',
                'group' => 'email',
                'label' => 'Reply To Address',
                'description' => 'Email address for replies',
                'is_public' => false,
            ],

            // Notification Settings
            [
                'key' => 'enable_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'Enable Notifications',
                'description' => 'Enable system notifications',
                'is_public' => false,
            ],
            [
                'key' => 'notification_email',
                'value' => 'admin@mindpark.com',
                'type' => 'email',
                'group' => 'notifications',
                'label' => 'Notification Email',
                'description' => 'Email address for system notifications',
                'is_public' => false,
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}