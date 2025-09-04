<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Str;

class GenerateBookingTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-booking-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate check_in_token for any bookings that are missing one.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding bookings with missing tokens...');

        $bookingsToUpdate = Booking::whereNull('check_in_token')->get();

        if ($bookingsToUpdate->isEmpty()) {
            $this->info('No bookings needed updating. All set!');
            return 0;
        }

        $this->info("Found {$bookingsToUpdate->count()} bookings to update. Starting...");

        $bookingsToUpdate->each(function ($booking) {
            $booking->check_in_token = Str::uuid();
            $booking->save();
        });

        $this->info('Successfully generated new tokens for all relevant bookings.');
        return 0;
    }
}
