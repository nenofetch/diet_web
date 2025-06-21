<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Snack;
use App\Models\History;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;

class SnackController extends Controller
{
    public function index()
    {
        $snacks = Snack::all();

        return ResponseFormatter::success(['snacks' => $snacks], 'Data berhasil ditampilkan!');
    }

    public function store(Request $request)
    {
        $category = $request->category;
        $selectedSnacks = $request->snacks;

        foreach ($selectedSnacks as $snackId) {
            $snack = Snack::find($snackId);

            if ($snack) {
                $histories[] = History::create([
                    'user_id' => Auth::user()->id,
                    'name' => $snack->name,
                    'category' => $category,
                    'calories' => $snack->calories,
                    'carbohydrates' => $snack->carbohydrate,
                    'protein' => $snack->proteins,
                    'fat' => $snack->fat,
                    'duration' => '0',
                    'tgl_input'=> $request->tgl_input
                ]);
            }
        }

        return ResponseFormatter::success(['histories' => $histories], 'Data berhasil disimpan!');
    }
}
