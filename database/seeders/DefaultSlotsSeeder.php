<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Temple;
use App\Models\DefaultDarshanSlot;
use Illuminate\Support\Facades\DB;

class DefaultSlotsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates a standard set of default darshan slots
     * for any temple that does not have any default slots configured yet.
     * It's safe to run multiple times.
     */
    public function run(): void
    {
        // Define the standard slots you want to create for each temple
        $standardSlots = [
            ['start_time' => '09:00:00', 'end_time' => '11:00:00', 'capacity' => 100, 'is_active' => true],
            ['start_time' => '11:00:00', 'end_time' => '13:00:00', 'capacity' => 100, 'is_active' => true],
            ['start_time' => '15:00:00', 'end_time' => '17:00:00', 'capacity' => 150, 'is_active' => true],
            ['start_time' => '17:00:00', 'end_time' => '19:00:00', 'capacity' => 150, 'is_active' => true],
        ];

        // Get all temples
        $temples = Temple::all();

        foreach ($temples as $temple) {
            // Check if this temple already has default slots
            $hasSlots = DefaultDarshanSlot::where('temple_id', $temple->id)->exists();

            // If it DOES NOT have slots, create them
            if (!$hasSlots) {
                $this->command->info("Creating default slots for: {$temple->name}");

                foreach ($standardSlots as $slot) {
                    DefaultDarshanSlot::create([
                        'temple_id' => $temple->id,
                        'start_time' => $slot['start_time'],
                        'end_time' => $slot['end_time'],
                        'capacity' => $slot['capacity'],
                        'is_active' => $slot['is_active'],
                    ]);
                }
            } else {
                 $this->command->info("Skipping {$temple->name}, default slots already exist.");
            }
        }
    }
}