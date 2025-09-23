<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\SettingsHelper;

class TestSettingsHelper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the Settings Helper functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Settings Helper...');
        
        // Test basic get/set
        $this->info('1. Testing basic get/set operations:');
        $originalValue = setting('company_name');
        $this->line("   Original company name: {$originalValue}");
        
        setting('test_setting', 'Test Value');
        $testValue = setting('test_setting');
        $this->line("   Test setting value: {$testValue}");
        
        // Test multiple settings
        $this->info('2. Testing multiple settings:');
        $multipleSettings = settings(['company_name', 'app_name', 'test_setting']);
        $this->line("   Multiple settings: " . json_encode($multipleSettings));
        
        // Test grouped settings
        $this->info('3. Testing grouped settings:');
        $companySettings = SettingsHelper::getCompanySettings();
        $this->line("   Company settings count: " . $companySettings->count());
        
        // Test search
        $this->info('4. Testing search functionality:');
        $searchResults = SettingsHelper::search('company');
        $this->line("   Search results for 'company': " . $searchResults->count() . " found");
        
        // Test validation
        $this->info('5. Testing validation:');
        $isValidEmail = SettingsHelper::validateValue('test@example.com', 'email');
        $this->line("   Email validation: " . ($isValidEmail ? 'Valid' : 'Invalid'));
        
        // Test count by group
        $this->info('6. Testing count by group:');
        $counts = SettingsHelper::getCountByGroup();
        foreach ($counts as $group => $count) {
            $this->line("   {$group}: {$count} settings");
        }
        
        // Clean up test setting
        setting_delete('test_setting');
        $this->info('7. Cleaned up test setting');
        
        $this->info('Settings Helper test completed successfully!');
    }
}
