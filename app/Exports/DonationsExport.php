<?php

namespace App\Exports;

use App\Models\Donation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DonationsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $donations;

    public function __construct($donations)
    {
        $this->donations = $donations;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->donations;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Devotee Name',
            'Devotee Email',
            'Amount',
            'Temple',
            'Purpose',
            'Donation Date',
        ];
    }

    /**
     * @param mixed $donation
     * @return array
     */
    public function map($donation): array
    {
        return [
            $donation->id,
            $donation->user->name ?? 'N/A',
            $donation->user->email ?? 'N/A',
            $donation->amount,
            $donation->temple->name ?? 'General Donation',
            $donation->purpose ? ucwords(str_replace('_', ' ', $donation->purpose)) : 'N/A',
            $donation->created_at->format('d-m-Y H:i A'),
        ];
    }
}
