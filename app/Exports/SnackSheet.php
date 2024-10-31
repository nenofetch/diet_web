<?php

namespace App\Exports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SnackSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        return History::where('category', 'LIKE', '%Cemilan%')->with('user')->get()->map(function ($history) {
            return [
                'nama_pengguna' => $history->user->name,
                'nama_cemilan' => $history->name,
                'kalori' => $history->calories,
                'karbohidrat' => $history->carbohydrates,
                'protein' => $history->protein,
                'lemak' => $history->fat,
                'dikonsumsi_pada' => $history->created_at->format('d-m-Y, H:i:s'),
            ];
        });
    }

    public function title(): string
    {
        return 'Data Cemilan';
    }


    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Cemilan',
            'Kalori',
            'Karbohidrat',
            'Protein',
            'Lemak',
            'Dikonsumsi Pada',
        ];
    }
}
