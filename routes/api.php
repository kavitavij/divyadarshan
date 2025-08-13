<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DarshanBookingController; // <-- Add this


Route::get('/temples/{temple}/slots', [DarshanBookingController::class, 'getSlots']);