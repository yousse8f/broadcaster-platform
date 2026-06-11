<?php

namespace App\Console\Commands;

use App\Mail\LicenseExpiringSoon;
use App\Models\License;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendExpirationWarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'licenses:send-expiration-warnings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send expiration warning emails for licenses about to expire';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for licenses about to expire...');

        $warningDays = [30, 7, 3, 1];
        $totalSent = 0;

        foreach ($warningDays as $days) {
            $targetDate = now()->addDays($days);

            // Find licenses that will expire in exactly $days days
            $licenses = License::where('status', 'active')
                ->whereDate('expires_at', $targetDate)
                ->with('user')
                ->get();

            $count = $licenses->count();

            if ($count > 0) {
                $this->info("Found {$count} license(s) expiring in {$days} day(s).");

                foreach ($licenses as $license) {
                    try {
                        if ($license instanceof License && $license->user) {
                            Mail::to($license->user->email)->send(new LicenseExpiringSoon($license, $days));
                            $this->info("Sent warning email for license {$license->license_key} to {$license->user->email}");
                            $totalSent++;
                        } else {
                            $this->error("License {$license->license_key} does not have a valid user or is not a proper License model.");
                        }
                    } catch (\Exception $e) {
                        $this->error("Failed to send email for license {$license->license_key}: {$e->getMessage()}");
                    }
                }
            }
        }

        $this->info("Expiration warning emails sent: {$totalSent}");

        return 0;
    }
}
