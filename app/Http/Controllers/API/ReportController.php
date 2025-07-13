<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\History;
use Carbon\Carbon;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        // Set locale to Indonesian
        Carbon::setLocale('id');

        // Calculate the start and end dates of the last week
        $startDate = now()->subWeek()->startOfWeek();
        $endDate = $startDate->copy()->addDays(6);

        // Single query to get all data for the week
        $histories = History::where('user_id', Auth::user()->id)
            ->whereBetween('created_at', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->groupBy(function ($history) {
                return $history->created_at->format('Y-m-d');
            });

        $calories = [];
        $carbohydrates = [];
        $protein = [];
        $fat = [];

        // Process data for each day
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dateString = $date->format('Y-m-d');

            // Get data for this specific date
            $dayHistories = $histories->get($dateString, collect());

            // Calculate totals
            $totalCalories = $dayHistories->sum('calories');
            $totalCarbohydrates = $dayHistories->sum('carbohydrates');
            $totalProtein = $dayHistories->sum('protein');
            $totalFat = $dayHistories->sum('fat');

            // Add data to arrays
            $calories[] = [
                'day' => $date->translatedFormat('l'),
                'total' => (int) $totalCalories,
            ];

            $carbohydrates[] = [
                'day' => $date->translatedFormat('l'),
                'total' => (int) $totalCarbohydrates,
            ];

            $protein[] = [
                'day' => $date->translatedFormat('l'),
                'total' => (int) $totalProtein,
            ];

            $fat[] = [
                'day' => $date->translatedFormat('l'),
                'total' => (int) $totalFat,
            ];
        }

        // Sort all arrays by day of the week (only once)
        $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $sortByDay = function ($item) use ($dayOrder) {
            return array_search($item['day'], $dayOrder);
        };

        $calories = collect($calories)->sortBy($sortByDay)->values()->all();
        $carbohydrates = collect($carbohydrates)->sortBy($sortByDay)->values()->all();
        $protein = collect($protein)->sortBy($sortByDay)->values()->all();
        $fat = collect($fat)->sortBy($sortByDay)->values()->all();

        $data = [
            'calories' => $calories,
            'carbohydrates' => $carbohydrates,
            'protein' => $protein,
            'fat' => $fat
        ];

        return ResponseFormatter::success($data, 'Data berhasil ditampilkan!');
    }

    public function show()
    {
        $today = now()->toDateString();
        $userId = Auth::user()->id;

        // Single query to get all data for today
        $allHistories = History::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('category');

        $data = [
            'breakfast' => $allHistories->get('Makan Pagi', collect()),
            'lunch' => $allHistories->get('Makan Siang', collect()),
            'dinner' => $allHistories->get('Makan Malam', collect()),
            'snack' => $allHistories->get('Cemilan', collect()),
            'drink' => $allHistories->get('Minuman', collect()),
            'sports' => $allHistories->get('Olahraga', collect()),
            'bmi' => $allHistories->get('BMI', collect()),
            'bmr' => $allHistories->get('BMR', collect())
        ];

        return ResponseFormatter::success($data, 'Data berhasil ditampilkan!');
    }
}
