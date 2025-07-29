<?php

namespace App\Exports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SportSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        // Set memory limit for this sheet
        ini_set('memory_limit', '256M');

        return History::where('category', 'LIKE', '%Olahraga%')
            ->with(['user:id,name,email'])
            ->orderBy('created_at', 'desc')
            ->limit(5000) // Limit to prevent memory issues
            ->get()
            ->map(function ($history) {
                return [
                'nama_pengguna' => $history->user->name ?? 'N/A',
                'jenis_olahraga' => $history->name,
                'durasi' => $history->duration,
                'tgl_input' => $history->tgl_input,
                    'tanggal_laporan' => $history->tgl_input,
                ];
            }); // Filter for Sport data
    }

    public function title(): string
    {
        return 'Data Olahraga';
    }


    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Jenis Olahraga',
            'Durasi',
            'Tanggal Input',
            'Tanggal Laporan'
        ];
    }
}
