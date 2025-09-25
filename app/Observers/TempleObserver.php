<?php

namespace App\Observers;

use App\Models\Temple;
use App\Models\DefaultDarshanSlot;

class TempleObserver
{
    /**
     * Handle the Temple "created" event.
     *
     * @param  \App\Models\Temple  $temple
     * @return void
     */
    public function created(Temple $temple): void
    {
        // Define the standard slots to be created for the new temple
        $standardSlots = [
            ['start_time' => '09:00:00', 'end_time' => '11:00:00', 'capacity' => 100, 'is_active' => true],
            ['start_time' => '11:00:00', 'end_time' => '13:00:00', 'capacity' => 100, 'is_active' => true],
            ['start_time' => '15:00:00', 'end_time' => '17:00:00', 'capacity' => 150, 'is_active' => true],
            ['start_time' => '17:00:00', 'end_time' => '19:00:00', 'capacity' => 150, 'is_active' => true],
        ];

        // Loop through the standard slots and create them for the newly created temple
        foreach ($standardSlots as $slot) {
            DefaultDarshanSlot::create([
                'temple_id' => $temple->id, // Use the ID of the temple that was just created
                'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time'],
                'capacity' => $slot['capacity'],
                'is_active' => $slot['is_active'],
            ]);
        }
    }
}