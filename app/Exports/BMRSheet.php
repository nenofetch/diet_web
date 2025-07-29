<?php

namespace App\Exports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BMRSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        // Set memory limit for this sheet
        ini_set('memory_limit', '256M');

        return History::where('name', 'LIKE', '%BMR %')
            ->with(['user:id,name,email'])
            ->orderBy('created_at', 'desc')
            ->limit(5000) // Limit to prevent memory issues
            ->get()
            ->map(function ($history) {
                return [
                'nama_pengguna' => $history->user->name ?? 'N/A',
                'jenis' => $history->name,
                'tinggi_badan' => $history->height,
                'berat_badan' => $history->weight,
                'hasil_bmr_dan_tdee' => $history->result_bmr,
                    'tanggal_laporan' => $history->tgl_input->format('d-m-Y, H:i:s'),
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
