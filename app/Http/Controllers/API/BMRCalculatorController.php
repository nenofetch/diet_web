<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\BMRCalculator;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BMRCalculatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $gender = Auth::user()->gender;
        $age = Carbon::parse(Auth::user()->date_of_birth)->age;
        $height = $request->height;
        $heightSquared = $height * $height;
        $weight = $request->weight;
        $activity = $request->activity;

        $activityWeekly = '';


        $bmr = '';

        $result = '';



        if ($gender == 'Laki-laki') {
            $bmr = 66.5 + (13.7 * $weight) + (6.25 * $heightSquared) - (6.8 * $age);
        } else {
            $bmr = 655 + (9.6 * $weight) + (1.8 * $heightSquared) - (4.7 * $age);
        }


        switch ($activity) {
            case 'Minimal':
                $activityWeekly = 1.2;
                break;
            case 'Ringan':
                $activityWeekly = 1.375;
                break;
            case 'Sedang':
                $activityWeekly = 1.55;
                break;
            case 'Berat':
                $activityWeekly = 1.725;
                break;
            case 'Ekstra Berat':
                $activityWeekly = 1.9;
                break;
            default:
                $activityWeekly = 1.2;
        }

        $result = $bmr * $activityWeekly;


        $data = [
            'result' => $result,
        ];

        History::create([
            'result_bmi' => '0',
            'result_bmr' => $result,
            'weight' => $weight,
            'height' => $height,
            'imt' => '0',
            'name' => 'BMR dan TDEE Kalkulator',
            'duration' => '0',
            'calories' => '0',
            'category' => 'BMR',
            'protein' => '0',
            'fat' => '0',
            'carbohydrates' => '0',
            'user_id' => Auth::user()->id,
        ]);


        return ResponseFormatter::success($data, 'Data berhasil ditambahkan!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $data = BMRCalculator::where('user_id', Auth::user()->id)->whereDate('created_at', now()->toDateString())->orderBy('created_at', 'asc')->get();


        return ResponseFormatter::success($data, 'Data berhasil ditampilkan!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
