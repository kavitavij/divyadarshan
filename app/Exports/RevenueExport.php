<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RevenueExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Combine all different transaction types into a single collection
        return $this->data['darshanBookings']
            ->concat($this->data['stayBookings'])
            ->concat($this->data['sevaBookings'])
            ->concat($this->data['donations'])
            ->sortByDesc('created_at');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date',
            'Type',
            'Description',
            'Venue',
            'Amount',
            'Status',
        ];
    }

    /**
     * @param mixed $item
     *
     * @return array
     */
    public function map($item): array
    {
        $type = 'Unknown';
        $description = 'N/A';
        $venue = 'N/A';

        // Format each row based on its object type
        if ($item instanceof \App\Models\Booking) {
            $type = 'Darshan';
            $description = 'Darshan for ' . $item->number_of_people . ' people';
            $venue = $item->temple->name ?? 'N/A';
        } elseif ($item instanceof \App\Models\StayBooking) {
            $type = 'Hotel Stay';
            $description = 'Booking for ' . ($item->room->type ?? 'Room');
            $venue = $item->hotel->name ?? 'N/A';
        } elseif ($item instanceof \App\Models\SevaBooking) {
            $type = 'Seva';
            $description = $item->seva->name ?? 'N/A';
            $venue = $item->seva->temple->name ?? 'N/A';
        } elseif ($item instanceof \App\Models\Donation) {
            $type = 'Donation';
            $description = $item->donation_purpose ?? 'General Donation';
            $venue = $item->temple->name ?? 'N/A';
        }

        return [
            $item->created_at->format('Y-m-d H:i'),
            $type,
            $description,
            $venue,
            $item->total_amount ?? $item->amount,
            $item->status,
        ];
    }
}
