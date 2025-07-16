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
        return History::where('category', 'LIKE', '%makan%')->with('user')->get()->map(function ($history) {
            return [
                'nama_pengguna' => $history->user->name,
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
