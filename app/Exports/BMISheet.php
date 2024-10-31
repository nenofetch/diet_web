<?php

namespace App\Exports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BMISheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        return History::where('name', 'LIKE', '%BMI%')->with('user')->get()->map(function ($history) {
            return [
                'nama_pengguna' => $history->user->name,
                'jenis' => $history->category,
                'tinggi_badan' => $history->height,
                'berat_badan' => $history->weight,
                'hasil_bmr_dan_tdee' => $history->result_bmi,
                'tanggal_laporan' => $history->created_at->format('d-m-Y, H:i:s'),
            ];
        }); // Filter for BMI data
    }

    public function title(): string
    {
        return 'Data BMI';
    }

    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Jenis',
            'Tinggi Badan/M',
            'Berat Badan/Kg',
            'BMI',
        ];
    }
}
