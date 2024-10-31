<?php

namespace App\Exports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BmrSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        return History::where('name', 'LIKE', '%BMR %')->with('user')->get()->map(function ($history) {
            return [
                'nama_pengguna' => $history->user->name,
                'jenis' => $history->name,
                'tinggi_badan' => $history->height,
                'berat_badan' => $history->weight,
                'hasil_bmr_dan_tdee' => $history->result_bmr,
                'tanggal_laporan' => $history->created_at->format('d-m-Y, H:i:s'),
            ];
        }); // Filter for BMR data
    }

    public function title(): string
    {
        return 'Data BMR';
    }


    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Jenis',
            'Tinggi Badan/M',
            'Berat Badan/Kg',
            'Hasil BMR dan TDEE',
            'Tanggal Laporan'
        ];
    }
}
