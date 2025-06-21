<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;
use App\Models\History;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;

class FoodController extends Controller
{
    public function index()
    {
        $foods = Food::all();

        return ResponseFormatter::success(['foods' => $foods], 'Data berhasil ditampilkan!');
    }

    public function store(Request $request)
    {
        $category = $request->category;
        $selectedFoods = $request->foods;

        foreach ($selectedFoods as $foodId) {
            $food = Food::find($foodId);

            if ($food) {
                $histories[] = History::create([
                    'user_id' => Auth::user()->id,
                    'name' => $food->name,
                    'category' => $category,
                    'calories' => $food->calories,
                    'carbohydrates' => $food->carbohydrate,
                    'protein' => $food->proteins,
                    'fat' => $food->fat,
                    'duration' => '0',
                    'tgl_input'=> $request->tgl_input,
                ]);
            }
        }

        return ResponseFormatter::success(['histories' => $histories], 'Data berhasil disimpan!');
    }
}
