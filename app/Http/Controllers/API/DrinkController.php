<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Drink;
use App\Models\History;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;

class DrinkController extends Controller
{
    public function index()
    {
        $drinks = Drink::all();

        return ResponseFormatter::success(['drinks' => $drinks], 'Data berhasil ditampilkan!');
    }

    public function store(Request $request)
    {
        $category = $request->category;
        $selectedDrinks = $request->drinks;

        foreach ($selectedDrinks as $drinkId) {
            $drink = Drink::find($drinkId);

            if ($drink) {
                $histories[] = History::create([
                    'user_id' => Auth::user()->id,
                    'name' => $drink->name,
                    'category' => $category,
                    'calories' => $drink->calories,
                    'carbohydrates' => $drink->carbohydrate,
                    'protein' => $drink->proteins,
                    'fat' => $drink->fat,
                    'duration' => '0',
                    'tgl_input'=> $request->tgl_input,
                ]);
            }
        }

        return ResponseFormatter::success(['histories' => $histories], 'Data berhasil disimpan!');
    }
}
