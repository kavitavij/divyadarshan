<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'bank_name',
        'status',
    ];

public function bookingable()
{
    return $this->morphTo('bookingable', 'booking_type', 'booking_id');
}
}
