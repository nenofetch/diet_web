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
        return EducationHistoryActivity::with('user')->get()->map(function ($history) {
            return [
                'nama_pengguna' => $history->user->name,
                'jenis_pendidikan' => $history->education_name,
                'tgl_input' => $history->tgl_input,
                'tanggal_laporan' => $history->created_at->format('d-m-Y, H:i:s'),
            ];
        }); // Filter for Sport data
    }

    public function title(): string
    {
        return 'Data Pendidikan';
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
