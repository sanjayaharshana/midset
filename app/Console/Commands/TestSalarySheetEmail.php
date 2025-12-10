<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SalarySheet;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SalarySheetCompleteNotification;
use Illuminate\Support\Facades\Log;

class TestSalarySheetEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:salary-sheet-email 
                            {--sheet-id= : The ID of the salary sheet to use for testing}
                            {--email= : Specific email address to send test email to}
                            {--all-reporters : Send to all reporters}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sending salary sheet complete notification email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Salary Sheet Email Test ===');
        $this->newLine();

        // Check mail configuration
        $mailDriver = config('mail.default');
        $this->info("Mail Driver: {$mailDriver}");
        $this->info("Mail From: " . config('mail.from.address'));
        $this->info("Mail From Name: " . config('mail.from.name'));
        $this->newLine();

        if ($mailDriver === 'log') {
            $this->warn('⚠️  WARNING: Mail driver is set to "log" - emails will be logged but not actually sent!');
            $this->warn('   Change MAIL_MAILER to "smtp" in .env to send real emails.');
            $this->newLine();
        }

        // Get salary sheet
        $sheetId = $this->option('sheet-id');
        if ($sheetId) {
            $salarySheet = SalarySheet::with(['job.client'])->find($sheetId);
            if (!$salarySheet) {
                $this->error("Salary sheet with ID {$sheetId} not found!");
                return 1;
            }
        } else {
            // Get the most recent salary sheet
            $salarySheet = SalarySheet::with(['job.client'])->latest()->first();
            if (!$salarySheet) {
                $this->error('No salary sheets found in database!');
                return 1;
            }
            $this->info("Using most recent salary sheet: {$salarySheet->sheet_no} (ID: {$salarySheet->id})");
        }

        $this->info("Sheet Number: {$salarySheet->sheet_no}");
        $this->info("Status: {$salarySheet->status}");
        $this->info("Job: " . ($salarySheet->job ? $salarySheet->job->job_number : 'N/A'));
        $this->newLine();

        // Determine recipient
        $testEmail = $this->option('email');
        $sendToAllReporters = $this->option('all-reporters');

        if ($testEmail) {
            // Send to specific email
            $this->info("Sending test email to: {$testEmail}");
            try {
                Mail::to($testEmail)->send(new SalarySheetCompleteNotification($salarySheet));
                $this->info("✅ Email sent successfully to {$testEmail}");
            } catch (\Exception $e) {
                $this->error("❌ Failed to send email: " . $e->getMessage());
                Log::error('Test email failed', [
                    'email' => $testEmail,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return 1;
            }
        } elseif ($sendToAllReporters) {
            // Send to all reporters
            $reporters = User::role('reporter')->get();
            
            if ($reporters->isEmpty()) {
                $this->error('No reporters found!');
                return 1;
            }

            $this->info("Found {$reporters->count()} reporter(s):");
            foreach ($reporters as $reporter) {
                $this->line("  - {$reporter->name} ({$reporter->email})");
            }
            $this->newLine();

            if (!$this->confirm('Send test email to all reporters?', true)) {
                $this->info('Cancelled.');
                return 0;
            }

            $successCount = 0;
            $failCount = 0;

            foreach ($reporters as $reporter) {
                try {
                    Mail::to($reporter->email)->send(new SalarySheetCompleteNotification($salarySheet));
                    $this->info("✅ Sent to: {$reporter->email}");
                    $successCount++;
                } catch (\Exception $e) {
                    $this->error("❌ Failed to send to {$reporter->email}: " . $e->getMessage());
                    $failCount++;
                    Log::error('Test email failed', [
                        'email' => $reporter->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $this->newLine();
            $this->info("Results: {$successCount} sent, {$failCount} failed");
        } else {
            // Interactive mode
            $this->info('Select recipient:');
            $this->line('1. Send to specific email address');
            $this->line('2. Send to all reporters');
            $this->line('3. Send to first reporter only (for quick testing)');
            $this->newLine();

            $choice = $this->choice('Choose an option', ['1', '2', '3'], '3');

            if ($choice === '1') {
                $testEmail = $this->ask('Enter email address');
                if ($testEmail) {
                    try {
                        Mail::to($testEmail)->send(new SalarySheetCompleteNotification($salarySheet));
                        $this->info("✅ Email sent successfully to {$testEmail}");
                    } catch (\Exception $e) {
                        $this->error("❌ Failed to send email: " . $e->getMessage());
                        return 1;
                    }
                }
            } elseif ($choice === '2') {
                $reporters = User::role('reporter')->get();
                if ($reporters->isEmpty()) {
                    $this->error('No reporters found!');
                    return 1;
                }
                foreach ($reporters as $reporter) {
                    try {
                        Mail::to($reporter->email)->send(new SalarySheetCompleteNotification($salarySheet));
                        $this->info("✅ Sent to: {$reporter->email}");
                    } catch (\Exception $e) {
                        $this->error("❌ Failed to send to {$reporter->email}: " . $e->getMessage());
                    }
                }
            } else {
                // Send to first reporter
                $reporter = User::role('reporter')->first();
                if (!$reporter) {
                    $this->error('No reporters found!');
                    return 1;
                }
                $this->info("Sending test email to first reporter: {$reporter->email}");
                try {
                    Mail::to($reporter->email)->send(new SalarySheetCompleteNotification($salarySheet));
                    $this->info("✅ Email sent successfully to {$reporter->email}");
                } catch (\Exception $e) {
                    $this->error("❌ Failed to send email: " . $e->getMessage());
                    return 1;
                }
            }
        }

        $this->newLine();
        $this->info('=== Test Complete ===');
        
        if ($mailDriver === 'log') {
            $this->warn('Note: Check storage/logs/laravel.log to see the logged email content.');
        }

        return 0;
    }
}
