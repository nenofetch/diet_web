<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HistoryExport implements WithMultipleSheets
{


    public function sheets(): array
    {
        return [
            new BMRSheet(),
            new BMISheet(),
            new FoodSheet(),
            new DrinkSheet(),
            new SnackSheet(),
            new SportSheet(),
        ];
    }

    // public function collection()
    // {
    //     // Fetch histories with the related user name
    //     return History::with('user')->get()->map(function ($history) {
    //         return [
    //             'category' => $history->category,
    //             'name' => $history->name,

    //             'user_name' => $history->user->name, // Assuming `user` relation has a `name` field
    //             'created_at' => $history->created_at->format('Y-m-d H:i:s'),
    //             'updated_at' => $history->updated_at->format('Y-m-d H:i:s'),
    //             // Add other fields from `History` model as needed
    //         ];
    //     });
    // }

    // /**
    //  * Define the headings for the Excel sheet.
    //  *
    //  * @return array
    //  */
    // public function headings(): array
    // {
    //     return [
    //         'Nama Pengguna',
    //         'Dibuat Pada',
    //         'Diupdate Pada',
    //         // Add other headings as needed
    //     ];
    // }
}
