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

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

}
