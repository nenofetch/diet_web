<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\History;

class FoodSheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        // Set memory limit for this sheet
        ini_set('memory_limit', '256M');

        return History::where('category', 'LIKE', '%makan%')
            ->with(['user:id,name,email'])
            ->orderBy('created_at', 'desc')
            ->limit(5000) // Limit to prevent memory issues
            ->get()
            ->map(function ($history) {
                return [
                    'nama_pengguna' => $history->user->name ?? 'N/A',
                    'waktu_makan' => $history->category,
                    'nama_makanan' => $history->name,
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
        return 'Data Makanan';
    }


    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Waktu Makan',
            'Makanan',
            'Kalori',
            'Karbohidrat',
            'Protein',
            'Lemak',
            'Dikonsumsi Pada',
        ];
    }
}
