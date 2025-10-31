<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'temple_id',
        'darshan_slot_id',
        'default_darshan_slot_id',
        'booking_date',
        'time_slot',
        'line_number',
        'number_of_people',
        'amount',
        'devotee_details',
        'status',
        'refund_status',
        'check_in_token',
        'checked_in_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'devotee_details' => 'array',
        'booking_date' => 'date',
        'checked_in_at' => 'datetime',
    ];

    // --- RELATIONSHIPS ---

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function devotees()
    {
        return $this->hasMany(Devotee::class);
    }

    public function refundRequests()
    {
        return $this->morphMany(RefundRequest::class, 'bookingable', 'booking_type', 'booking_id');
    }

    /**
     * Get the specific (custom) darshan slot for the booking.
     */
    public function darshanSlot()
    {
        return $this->belongsTo(DarshanSlot::class);
    }

    /**
     * Get the default darshan slot for the booking.
     */
    public function defaultDarshanSlot()
    {
        return $this->belongsTo(DefaultDarshanSlot::class);
    }

    // --- ACCESSORS ---

    /**
     * NEW ACCESSOR: This creates a "virtual" `slot` attribute.
     * It intelligently returns whichever slot relationship (custom or default) is not null.
     * This fixes the "undefined relationship [slot]" error.
     */
    public function getSlotAttribute()
    {
        return $this->darshanSlot ?? $this->defaultDarshanSlot;
    }

    /**
     * This "virtual" attribute provides the correct slot time.
     * It checks if a custom slot or a default slot is linked and returns its time.
     * Your dashboard view uses this to display the correct information.
     */
    public function getSlotTimeAttribute(): string
    {
        // This is now simpler because it uses the new `slot` accessor above.
        if ($this->slot) {
            return Carbon::parse($this->slot->start_time)->format('h:i A') . ' - ' . Carbon::parse($this->slot->end_time)->format('h:i A');
        }

        return 'N/A';
    }
    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class);
    }
}

