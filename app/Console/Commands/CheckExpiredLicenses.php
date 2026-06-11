<?php

namespace App\Console\Commands;

use App\Mail\LicenseExpired;
use App\Models\License;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckExpiredLicenses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'licenses:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and expire licenses that have passed their expiration date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired licenses...');

        // Find active licenses that have expired
        $expiredLicenses = License::where('status', 'active')
            ->where('expires_at', '<', now())
            ->with('user')
            ->get();

        $count = $expiredLicenses->count();

        if ($count > 0) {
            foreach ($expiredLicenses as $license) {
                $license->update(['status' => 'expired']);

                // Send expired email notification
                try {
                    Mail::to($license->user->email)->send(new LicenseExpired($license));
                    $this->info("License {$license->license_key} has been expired and email sent to {$license->user->email}.");
                } catch (\Exception $e) {
                    $this->info("License {$license->license_key} has been expired but email failed: {$e->getMessage()}");
                }
            }

            $this->info("Expired {$count} license(s).");
        } else {
            $this->info('No expired licenses found.');
        }

        return 0;
    }
}
