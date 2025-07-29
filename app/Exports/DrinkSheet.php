<?php

namespace App\Exports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DrinkSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        // Set memory limit for this sheet
        ini_set('memory_limit', '256M');

        return History::where('category', 'LIKE', '%Minuman%')
            ->with(['user:id,name,email'])
            ->orderBy('created_at', 'desc')
            ->limit(5000) // Limit to prevent memory issues
            ->get()
            ->map(function ($history) {
                return [
                    'nama_pengguna' => $history->user->name ?? 'N/A',
                    'nama_minuman' => $history->name,
                    'kalori' => $history->calories,
                    'karbohidrat' => $history->carbohydrates,
                    'protein' => $history->protein,
                    'lemak' => $history->fat,
                    'dikonsumsi_pada' => $history->tgl_input,
                ];
            });
    }

    public function title(): string
    {
        return 'Data Minuman';
    }


    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Minuman',
            'Kalori',
            'Karbohidrat',
            'Protein',
            'Lemak',
            'Dikonsumsi Pada',
        ];
    }
}
