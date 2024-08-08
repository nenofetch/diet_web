<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Anthropometry;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class AnthropometryController extends Controller
{
    public function index(Request $request)
    {
        $weight = $request->weight; // Body weight in kilograms (kg)
        $height = $request->height; // Height in meters (m)

        // Squares height in meters
        $heightSquared = $height * $height;

        // Calculate BMI
        $imt = $weight / $heightSquared;

        $result = '';

        if ($imt < 18.5) {
            $result = 'Underweight';
        } elseif ($imt >= 18.5 && $imt <= 22.9) {
            $result = 'Normal';
        } elseif ($imt >= 23 && $imt <= 28.9) {
            $result = 'Overweight';
        } elseif ($imt >= 29 && $imt <= 33.9) {
            $result = 'Obesity Class I';
        } elseif ($imt >= 34 && $imt <= 38.9) {
            $result = 'Obesity Class II';
        } elseif ($imt >= 39) {
            $result = 'Obesity Class III';
        }

        $data = [
            'imt' => $imt,
            'result' => $result,
        ];



        History::create([
            'result_bmi' => $result,
            'result_bmr' => '0',
            'weight' => $weight,
            'height' => $height,
            'imt' => $imt,
            'name' => 'BMI Kalkulator',
            'duration' => '0',
            'calories' => '0',
            'category' => 'BMI',
            'protein' => '0',
            'fat' => '0',
            'carbohydrates' => '0',
            'user_id' => Auth::user()->id,
        ]);

        return ResponseFormatter::success($data, 'Kalkulasi IMT berhasil!');
    }

    public function show()
    {
        $anthropometry = Anthropometry::where('user_id', Auth::user()->id)->whereDate('created_at', now()->toDateString())->orderBy('created_at', 'asc')->get();

        return ResponseFormatter::success($anthropometry, 'Data IMT berhasil diambil');
    }


    // public function store(Request $request)
    // {
    //     $data = $request->all();


    // }
}
