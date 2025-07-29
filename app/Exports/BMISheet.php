<?php

namespace App\Exports;

use App\Models\History;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BMISheet implements FromCollection, WithTitle, WithHeadings
{
    public function collection()
    {
        // Set memory limit for this sheet
        ini_set('memory_limit', '256M');

        return History::where('category', 'BMI')
            ->with(['user:id,name,email'])
            ->orderBy('tgl_input', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5000) // Limit to prevent memory issues
            ->get()
            ->map(function ($history) {
                // Extract BMI type from name (Pre-test or Post-test)
                $nameArray = json_decode($history->name, true);
                $bmiType = is_array($nameArray) && !empty($nameArray) ? $nameArray[0] : 'BMI';

                return [
                'nama_pengguna' => $history->user->name ?? 'N/A',
                'jenis_test' => $bmiType,
                'tinggi_badan' => $history->height,
                'berat_badan' => $history->weight,
                    'hasil_bmi' => $history->result_bmi,
                    'tanggal_test' => $history->tgl_input,
                ];
            }); // Filter for BMI data
    }

    public function title(): string
    {
        return 'Data BMI';
    }

    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Jenis Test',
            'Tinggi Badan/M',
            'Berat Badan/Kg',
            'BMI',
            'Tanggal Test',
        ];
    }
}
