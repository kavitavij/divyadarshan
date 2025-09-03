<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Temple;
use App\Models\DefaultDarshanSlot;

class DefaultDarshanSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the 4 default slot templates
        $defaultSlotsTemplate = [
            ['start_time' => '09:00:00', 'end_time' => '11:00:00', 'capacity' => 1000, 'is_active' => true],
            ['start_time' => '11:00:00', 'end_time' => '13:00:00', 'capacity' => 1000, 'is_active' => true],
            ['start_time' => '15:00:00', 'end_time' => '17:00:00', 'capacity' => 1000, 'is_active' => true],
            ['start_time' => '17:00:00', 'end_time' => '19:00:00', 'capacity' => 1000, 'is_active' => true],
        ];

        // Get all temples from the database
        $temples = Temple::all();

        // Loop through each temple
        foreach ($temples as $temple) {
            $this->command->info("Checking default slots for: {$temple->name}");

            // Loop through the templates and create the slots for the current temple
            foreach ($defaultSlotsTemplate as $slotData) {
                // Use updateOrCreate to avoid creating duplicates if you run the seeder again
                DefaultDarshanSlot::updateOrCreate(
                    [
                        'temple_id' => $temple->id,
                        'start_time' => $slotData['start_time'],
                    ],
                    [
                        'end_time' => $slotData['end_time'],
                        'capacity' => $slotData['capacity'],
                        'is_active' => $slotData['is_active'],
                    ]
                );
            }
        }
    }
}
