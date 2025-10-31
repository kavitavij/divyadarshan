<?php

namespace App\Exports;

use App\Models\StayBooking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HotelRevenueExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bookings;

    public function __construct($bookings)
    {
        $this->bookings = $bookings;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->bookings;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Booking ID',
            'Guest Name',
            'Guest Email',
            'Hotel',
            'Room',
            'Check-in Date',
            'Check-out Date',
            'Status',
            'Total Amount',
            'Payment Method',
            'Booked At',
        ];
    }

    /**
     * @param StayBooking $booking
     * @return array
     */
    public function map($booking): array
    {
        return [
            $booking->id,
            $booking->user->name,
            $booking->user->email,
            $booking->hotel->name,
            $booking->room->room_type,
            $booking->check_in_date,
            $booking->check_out_date,
            $booking->status,
            $booking->total_amount,
            $booking->payment_method,
            $booking->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
