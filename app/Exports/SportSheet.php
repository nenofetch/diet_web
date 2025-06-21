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
        return History::where('category', 'LIKE', '%Olahraga%')->with('user')->get()->map(function ($history) {
            return [
                'nama_pengguna' => $history->user->name,
                'jenis_olahraga' => $history->name,
                'durasi' => $history->duration,
                'tgl_input' => $history->tgl_input,
                'tanggal_laporan' => $history->created_at->format('d-m-Y, H:i:s'),
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
