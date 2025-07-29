<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Food;
use App\Models\History;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- MAIN FOODS SEEDING (Batch Insert) ---
        $totalRecords = 44604;
        $categories = ['Makan Pagi', 'Makan Siang', 'Makan Malam'];
        $startDate = Carbon::create(2024, 11, 1);
        $endDate = Carbon::create(2025, 2, 28);
        $days = $startDate->diffInDays($endDate) + 1;
        $recordsPerDay = intdiv($totalRecords, $days);
        $recordsPerCategoryPerDay = intdiv($recordsPerDay, count($categories));
        $remaining = $totalRecords - ($recordsPerDay * $days);
        $users = User::whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])->pluck('id')->toArray();
        if (count($users) === 0) return;
        $foods = Food::all();
        if ($foods->count() === 0) return;
        $foodArr = $foods->toArray();
        $date = $startDate->copy();
        $userIdx = 0;
        $leftover = $remaining;
        for ($d = 0; $d < $days; $d++) {
            foreach ($categories as $catIdx => $category) {
                $recordsToday = $recordsPerCategoryPerDay + ($leftover > 0 ? 1 : 0);
                if ($leftover > 0) $leftover--;
                $batch = [];
                for ($i = 0; $i < $recordsToday; $i++) {
                    $user_id = $users[$userIdx % count($users)];
                    $userIdx++;
                    $numFoods = rand(1, 3);
                    $foodNames = [];
                    $calories = 0;
                    $carbohydrates = 0;
                    $protein = 0;
                    $fat = 0;
                    for ($f = 0; $f < $numFoods; $f++) {
                        $food = $foodArr[array_rand($foodArr)];
                        $foodNames[] = $food['name'];
                        $calories += (float) $food['calories'];
                        $carbohydrates += (float) $food['carbohydrate'];
                        $protein += (float) $food['proteins'];
                        $fat += (float) $food['fat'];
                    }
                    $batch[] = [
                        'user_id' => $user_id,
                        'name' => json_encode($foodNames),
                        'category' => $category,
                        'calories' => $calories,
                        'carbohydrates' => $carbohydrates,
                        'protein' => $protein,
                        'fat' => $fat,
                        'duration' => 0,
                        'result_bmr' => 0,
                        'result_bmi' => 0,
                        'weight' => 0,
                        'height' => 0,
                        'imt' => 0,
                        'tgl_input' => $date->format('Y-m-d'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    if (count($batch) === 500) {
                        History::insert($batch);
                        $batch = [];
                        gc_collect_cycles();
                    }
                }
                if (count($batch)) {
                    History::insert($batch);
                    gc_collect_cycles();
                }
            }
            $date->addDay();
        }

        // --- SNACK REPORT SEEDING (Batch Insert) ---
        $snackTotal = 14616;
        $snackCategory = 'Cemilan';
        $snackRecordsPerDay = intdiv($snackTotal, $days);
        $snackLeftover = $snackTotal - ($snackRecordsPerDay * $days);
        $snackDate = $startDate->copy();
        $snackUserIdx = 0;
        for ($d = 0; $d < $days; $d++) {
            $recordsToday = $snackRecordsPerDay + ($snackLeftover > 0 ? 1 : 0);
            if ($snackLeftover > 0) $snackLeftover--;
            $batch = [];
            for ($i = 0; $i < $recordsToday; $i++) {
                $user_id = $users[$snackUserIdx % count($users)];
                $snackUserIdx++;
                $numFoods = rand(1, 3);
                $foodNames = [];
                $calories = 0;
                $carbohydrates = 0;
                $protein = 0;
                $fat = 0;
                for ($f = 0; $f < $numFoods; $f++) {
                    $food = $foodArr[array_rand($foodArr)];
                    $foodNames[] = $food['name'];
                    $calories += (float) $food['calories'];
                    $carbohydrates += (float) $food['carbohydrate'];
                    $protein += (float) $food['proteins'];
                    $fat += (float) $food['fat'];
                }
                $batch[] = [
                    'user_id' => $user_id,
                    'name' => json_encode($foodNames),
                    'category' => $snackCategory,
                    'calories' => $calories,
                    'carbohydrates' => $carbohydrates,
                    'protein' => $protein,
                    'fat' => $fat,
                    'duration' => 0,
                    'result_bmr' => 0,
                    'result_bmi' => 0,
                    'weight' => 0,
                    'height' => 0,
                    'imt' => 0,
                    'tgl_input' => $snackDate->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if (count($batch) === 500) {
                    History::insert($batch);
                    $batch = [];
                    gc_collect_cycles();
                }
            }
            if (count($batch)) {
                History::insert($batch);
                gc_collect_cycles();
            }
            $snackDate->addDay();
        }

        // --- DRINKS REPORT SEEDING (Batch Insert) ---
        $drinkTotal = 55000;
        $drinkCategory = 'Minuman';
        $drinkRecordsPerDay = intdiv($drinkTotal, $days);
        $drinkLeftover = $drinkTotal - ($drinkRecordsPerDay * $days);
        $drinkDate = $startDate->copy();
        $drinkUserIdx = 0;
        for ($d = 0; $d < $days; $d++) {
            $recordsToday = $drinkRecordsPerDay + ($drinkLeftover > 0 ? 1 : 0);
            if ($drinkLeftover > 0) $drinkLeftover--;
            $batch = [];
            for ($i = 0; $i < $recordsToday; $i++) {
                $user_id = $users[$drinkUserIdx % count($users)];
                $drinkUserIdx++;
                $numFoods = rand(1, 3);
                $foodNames = [];
                $calories = 0;
                $carbohydrates = 0;
                $protein = 0;
                $fat = 0;
                for ($f = 0; $f < $numFoods; $f++) {
                    $food = $foodArr[array_rand($foodArr)];
                    $foodNames[] = $food['name'];
                    $calories += (float) $food['calories'];
                    $carbohydrates += (float) $food['carbohydrate'];
                    $protein += (float) $food['proteins'];
                    $fat += (float) $food['fat'];
                }
                $batch[] = [
                    'user_id' => $user_id,
                    'name' => json_encode($foodNames),
                    'category' => $drinkCategory,
                    'calories' => $calories,
                    'carbohydrates' => $carbohydrates,
                    'protein' => $protein,
                    'fat' => $fat,
                    'duration' => 0,
                    'result_bmr' => 0,
                    'result_bmi' => 0,
                    'weight' => 0,
                    'height' => 0,
                    'imt' => 0,
                    'tgl_input' => $drinkDate->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if (count($batch) === 500) {
                    History::insert($batch);
                    $batch = [];
                    gc_collect_cycles();
                }
            }
            if (count($batch)) {
                History::insert($batch);
                gc_collect_cycles();
            }
            $drinkDate->addDay();
        }

        // --- SPORT ACTION SEEDING (Batch Insert) ---
        $sportTotal = 1764;
        $sportCategory = 'Olahraga';
        $sportStartDate = Carbon::create(2024, 11, 1);
        $sportEndDate = Carbon::today();
        $sportDays = $sportStartDate->diffInDays($sportEndDate) + 1;
        $sportRecordsPerDay = intdiv($sportTotal, $sportDays);
        $sportLeftover = $sportTotal - ($sportRecordsPerDay * $sportDays);
        $sportDate = $sportStartDate->copy();
        $sportUserIdx = 0;
        $usersWithGender = User::whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])
            ->select('id', 'gender')
            ->get();
        if ($usersWithGender->count() === 0) return;
        $sports = \App\Models\Sport::all();
        if ($sports->count() === 0) return;
        $sportsArr = $sports->toArray();
        for ($d = 0; $d < $sportDays; $d++) {
            $recordsToday = $sportRecordsPerDay + ($sportLeftover > 0 ? 1 : 0);
            if ($sportLeftover > 0) $sportLeftover--;
            $batch = [];
            for ($i = 0; $i < $recordsToday; $i++) {
                $user = $usersWithGender[$sportUserIdx % $usersWithGender->count()];
                $sportUserIdx++;
                $availableSports = $user->gender === 'Perempuan'
                    ? array_values(array_filter($sportsArr, function ($s) {
                        return $s['name'] !== 'Sepak bola';
                    }))
                    : $sportsArr;
                if (empty($availableSports)) continue;
                $sport = $availableSports[array_rand($availableSports)];
                $durationMin = rand(1, 8) * 15;
                if ($durationMin <= 15) {
                    $calories = $sport['five_minute_calories'];
                } elseif ($durationMin <= 30) {
                    $calories = $sport['fifteen_minute_calories'];
                } elseif ($durationMin <= 45) {
                    $calories = $sport['thirty_minute_calories'];
                } else {
                    $calories = $sport['one_hour_calories'];
                }
                $batch[] = [
                    'user_id' => $user->id,
                    'name' => json_encode([$sport['name']]),
                    'category' => $sportCategory,
                    'duration' => $durationMin,
                    'calories' => $calories,
                    'protein' => 0,
                    'fat' => 0,
                    'carbohydrates' => 0,
                    'result_bmr' => 0,
                    'result_bmi' => 0,
                    'weight' => 0,
                    'height' => 0,
                    'imt' => 0,
                    'tgl_input' => $sportDate->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if (count($batch) === 500) {
                    History::insert($batch);
                    $batch = [];
                    gc_collect_cycles();
                }
            }
            if (count($batch)) {
                History::insert($batch);
                gc_collect_cycles();
            }
            $sportDate->addDay();
        }

        // --- EDUCATION HISTORY ACTIVITY SEEDING (Batch Insert) ---
        $educationTotal = 4944;
        $educationStartDate = Carbon::create(2024, 11, 1);
        $educationEndDate = Carbon::today();
        $educationDays = $educationStartDate->diffInDays($educationEndDate) + 1;
        $educationRecordsPerDay = intdiv($educationTotal, $educationDays);
        $educationLeftover = $educationTotal - ($educationRecordsPerDay * $educationDays);
        $educationDate = $educationStartDate->copy();
        $educationUserIdx = 0;
        $eduUsers = User::whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])->pluck('id')->toArray();
        if (count($eduUsers) === 0) return;
        $allEducations = \App\Models\Education::all();
        if ($allEducations->count() === 0) return;
        $mealPlans = $allEducations->where('title', 'like', '%Meal Plan%')->pluck('title')->take(9)->toArray();
        $senam = $allEducations->filter(function ($e) {
            return stripos($e->title, 'Senam') !== false || stripos($e->title, 'Olahraga') !== false || stripos($e->title, 'Pemanasan') !== false;
        })->pluck('title')->take(3)->toArray();
        $excluded = array_merge($mealPlans, $senam);
        $edukasi = $allEducations->filter(function ($e) use ($excluded) {
            return !in_array($e->title, $excluded);
        })->pluck('title')->shuffle()->take(15)->toArray();
        $educationNames = array_merge($mealPlans, $senam, $edukasi);
        if (count($educationNames) === 0) return;
        for ($d = 0; $d < $educationDays; $d++) {
            $recordsToday = $educationRecordsPerDay + ($educationLeftover > 0 ? 1 : 0);
            if ($educationLeftover > 0) $educationLeftover--;
            $batch = [];
            for ($i = 0; $i < $recordsToday; $i++) {
                $user_id = $eduUsers[$educationUserIdx % count($eduUsers)];
                $educationUserIdx++;
                $education_name = $educationNames[array_rand($educationNames)];
                $batch[] = [
                    'user_id' => $user_id,
                    'education_name' => $education_name,
                    'tgl_input' => $educationDate->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if (count($batch) === 500) {
                    \App\Models\EducationHistoryActivity::insert($batch);
                    $batch = [];
                    gc_collect_cycles();
                }
            }
            if (count($batch)) {
                \App\Models\EducationHistoryActivity::insert($batch);
                gc_collect_cycles();
            }
            $educationDate->addDay();
        }

        // --- PRETEST & POSTTEST (BMI) SEEDING (Batch Insert) ---
        $csvFiles = [
            ['file' => base_path('database/seeders/csv/pretest.csv'), 'category' => 'BMI', 'tgl_input' => '2024-11-15'],
            ['file' => base_path('database/seeders/csv/post.csv'), 'category' => 'BMI', 'tgl_input' => '2025-02-15'],
        ];
        $userMap = User::whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])
            ->get()
            ->mapWithKeys(function ($u) {
                return [strtolower(trim($u->name)) => $u->id];
            });
        foreach ($csvFiles as $csvInfo) {
            if (!file_exists($csvInfo['file'])) continue;
            $handle = fopen($csvInfo['file'], 'r');
            $header = fgetcsv($handle, 0, ';');
            $peek = fgetcsv($handle, 0, ';');
            if (empty(array_filter($peek))) $peek = fgetcsv($handle, 0, ';');
            else fseek($handle, -strlen(implode(';', $peek)) - 1, SEEK_CUR);
            $batch = [];
            $matched = 0;
            $unmatched = [];
            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                if (count($row) < 7) continue;
                $name = strtolower(trim($row[1]));
                $user_id = $userMap[$name] ?? null;
                if (!$user_id) {
                    $unmatched[] = $row[1];
                    continue;
                }
                $matched++;
                $weight = isset($row[4]) ? str_replace(',', '.', $row[4]) : null;
                $height = isset($row[5]) ? str_replace(',', '.', $row[5]) : null;
                $imt = str_replace([','], ['.'], $row[6] ?? null);
                $result_bmi = $row[7] ?? null;
                $batch[] = [
                    'user_id' => $user_id,
                    'name' => json_encode(['BMI Kalkulator']),
                    'category' => 'BMI',
                    'calories' => 0,
                    'carbohydrates' => 0,
                    'protein' => 0,
                    'fat' => 0,
                    'duration' => 0,
                    'result_bmr' => 0,
                    'result_bmi' => $result_bmi,
                    'weight' => $weight,
                    'height' => $height,
                    'imt' => $imt,
                    'tgl_input' => $csvInfo['tgl_input'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                if (count($batch) === 500) {
                    History::insert($batch);
                    $batch = [];
                    gc_collect_cycles();
                }
            }
            if (count($batch)) {
                History::insert($batch);
                gc_collect_cycles();
            }
            fclose($handle);
            Log::info('BMI Seeder: Matched users: ' . $matched . ' for file: ' . $csvInfo['file']);
            if (!empty($unmatched)) {
                Log::info('BMI Seeder: Unmatched users: ' . implode(', ', $unmatched));
            }
        }
    }
}
