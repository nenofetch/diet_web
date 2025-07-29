<?php

namespace App\Exports;

use App\Models\EducationHistoryActivity;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EducationSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        // Set memory limit for this sheet
        ini_set('memory_limit', '256M');

        return EducationHistoryActivity::with(['user:id,name,email'])
            ->orderBy('created_at', 'desc')
            ->limit(5000) // Limit to prevent memory issues
            ->get()
            ->map(function ($history) {
                return [
                    'nama_pengguna' => $history->user->name ?? 'N/A',
                    'jenis_pendidikan' => $history->education_name,
                    'tgl_input' => $history->tgl_input,
                    'tanggal_laporan' => $history->created_at->format('d-m-Y, H:i:s'),
                ];
            }); // Filter for Sport data
    }

    public function title(): string
    {
        return 'Akses Edukasi';
    }


    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Nama Kegiatan',
            'Tanggal Kegiatan',
            'Tanggal Laporan'
        ];
    }
}
