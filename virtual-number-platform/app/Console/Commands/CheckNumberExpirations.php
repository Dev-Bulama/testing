<?php

namespace App\Console\Commands;

use App\Models\PhoneNumber;
use App\Notifications\NumberExpiredNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckNumberExpirations extends Command
{
    protected $signature = 'numbers:check-expirations';

    protected $description = 'Check for expired numbers and send notifications';

    public function handle(): int
    {
        $now = Carbon::now();
        PhoneNumber::with(['customer'])
            ->whereNotNull('rented_by')
            ->where('expires_at', '<', $now)
            ->chunk(100, function ($numbers) {
                foreach ($numbers as $number) {
                    if ($number->customer) {
                        $number->customer->notify(new NumberExpiredNotification($number));
                    }

                    $number->forceFill([
                        'status' => PhoneNumber::STATUS_AVAILABLE,
                        'rented_by' => null,
                        'rented_at' => null,
                        'expires_at' => null,
                    ])->save();

                    $this->info("Released number {$number->number}.");
                }
            });

        return Command::SUCCESS;
    }
}
