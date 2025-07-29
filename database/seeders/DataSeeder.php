<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Food;
use App\Models\History;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class DataSeeder extends Seeder
{
    private $batchSize = 1000; // Increased batch size for better performance
    private $maxExecutionTime = 300; // 5 minutes max execution time
    private $startTime;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->startTime = time();

        try {
            // Disable foreign key checks and autocommit for better performance
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::statement('SET AUTOCOMMIT = 0');

            // Increase MySQL timeout (packet size requires GLOBAL privileges)
            DB::statement('SET SESSION wait_timeout = 600');

            $this->seedMainFoods();
            $this->seedSnackReports();
            $this->seedDrinkReports();
            $this->seedSportActions();
            $this->seedEducationHistory();
            $this->seedBMIData();

            // Re-enable foreign key checks and autocommit
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            DB::commit();

            Log::info('DataSeeder completed successfully');
        } catch (Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            Log::error('DataSeeder failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check execution time and memory usage
     */
    private function checkExecutionTime(): void
    {
        if (time() - $this->startTime > $this->maxExecutionTime) {
            throw new Exception('Seeder execution time exceeded limit');
        }

        if (memory_get_usage(true) > 512 * 1024 * 1024) { // 512MB limit
            gc_collect_cycles();
        }
    }

    /**
     * Safe batch insert with error handling
     */
    private function safeBatchInsert(string $table, array $batch): void
    {
        if (empty($batch)) return;

        try {
            DB::table($table)->insert($batch);
            $this->checkExecutionTime();
        } catch (Exception $e) {
            Log::error("Failed to insert batch into {$table}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Seed main foods data
     */
    private function seedMainFoods(): void
    {
        Log::info('Starting main foods seeding...');

        $totalRecords = 44604;
        $categories = ['Makan Pagi', 'Makan Siang', 'Makan Malam'];
        $startDate = Carbon::create(2024, 11, 1);
        $endDate = Carbon::create(2025, 2, 28);
        $days = $startDate->diffInDays($endDate) + 1;
        $recordsPerDay = intdiv($totalRecords, $days);
        $recordsPerCategoryPerDay = intdiv($recordsPerDay, count($categories));
        $remaining = $totalRecords - ($recordsPerDay * $days);

        $users = User::whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])->pluck('id')->toArray();
        if (count($users) === 0) {
            Log::warning('No users found for main foods seeding');
            return;
        }

        $foods = Food::all();
        if ($foods->count() === 0) {
            Log::warning('No foods found for main foods seeding');
            return;
        }
        $foodArr = $foods->toArray();

        $date = $startDate->copy();
        $userIdx = 0;
        $leftover = $remaining;
        $batch = [];
        $insertedCount = 0;

        for ($d = 0; $d < $days; $d++) {
            foreach ($categories as $category) {
                $recordsToday = $recordsPerCategoryPerDay + ($leftover > 0 ? 1 : 0);
                if ($leftover > 0) $leftover--;

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

                    if (count($batch) >= $this->batchSize) {
                        $this->safeBatchInsert('histories', $batch);
                        $insertedCount += count($batch);
                        $batch = [];
                        Log::info("Main foods: Inserted {$insertedCount} records");
                    }
                }
            }
            $date->addDay();
        }

        if (count($batch)) {
            $this->safeBatchInsert('histories', $batch);
            $insertedCount += count($batch);
        }

        Log::info("Main foods seeding completed: {$insertedCount} records");
    }

    /**
     * Seed snack reports data
     */
    private function seedSnackReports(): void
    {
        Log::info('Starting snack reports seeding...');

        $snackTotal = 14616;
        $snackCategory = 'Cemilan';
        $startDate = Carbon::create(2024, 11, 1);
        $endDate = Carbon::create(2025, 2, 28);
        $days = $startDate->diffInDays($endDate) + 1;
        $snackRecordsPerDay = intdiv($snackTotal, $days);
        $snackLeftover = $snackTotal - ($snackRecordsPerDay * $days);

        $users = User::whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])->pluck('id')->toArray();
        if (count($users) === 0) return;

        $foods = Food::all();
        if ($foods->count() === 0) return;
        $foodArr = $foods->toArray();

        $snackDate = $startDate->copy();
        $snackUserIdx = 0;
        $batch = [];
        $insertedCount = 0;

        for ($d = 0; $d < $days; $d++) {
            $recordsToday = $snackRecordsPerDay + ($snackLeftover > 0 ? 1 : 0);
            if ($snackLeftover > 0) $snackLeftover--;

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

                if (count($batch) >= $this->batchSize) {
                    $this->safeBatchInsert('histories', $batch);
                    $insertedCount += count($batch);
                    $batch = [];
                }
            }
            $snackDate->addDay();
        }

        if (count($batch)) {
            $this->safeBatchInsert('histories', $batch);
            $insertedCount += count($batch);
        }

        Log::info("Snack reports seeding completed: {$insertedCount} records");
    }

    /**
     * Seed drink reports data
     */
    private function seedDrinkReports(): void
    {
        Log::info('Starting drink reports seeding...');

        $drinkTotal = 55000;
        $drinkCategory = 'Minuman';
        $startDate = Carbon::create(2024, 11, 1);
        $endDate = Carbon::create(2025, 2, 28);
        $days = $startDate->diffInDays($endDate) + 1;
        $drinkRecordsPerDay = intdiv($drinkTotal, $days);
        $drinkLeftover = $drinkTotal - ($drinkRecordsPerDay * $days);

        $users = User::whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])->pluck('id')->toArray();
        if (count($users) === 0) return;

        $foods = Food::all();
        if ($foods->count() === 0) return;
        $foodArr = $foods->toArray();

        $drinkDate = $startDate->copy();
        $drinkUserIdx = 0;
        $batch = [];
        $insertedCount = 0;

        for ($d = 0; $d < $days; $d++) {
            $recordsToday = $drinkRecordsPerDay + ($drinkLeftover > 0 ? 1 : 0);
            if ($drinkLeftover > 0) $drinkLeftover--;

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

                if (count($batch) >= $this->batchSize) {
                    $this->safeBatchInsert('histories', $batch);
                    $insertedCount += count($batch);
                    $batch = [];
                }
            }
            $drinkDate->addDay();
        }

        if (count($batch)) {
            $this->safeBatchInsert('histories', $batch);
            $insertedCount += count($batch);
        }

        Log::info("Drink reports seeding completed: {$insertedCount} records");
    }

    /**
     * Seed sport actions data
     */
    private function seedSportActions(): void
    {
        Log::info('Starting sport actions seeding...');

        $sportTotal = 1764;
        $sportCategory = 'Olahraga';
        $sportStartDate = Carbon::create(2024, 11, 1);
        $sportEndDate = Carbon::today();
        $sportDays = $sportStartDate->diffInDays($sportEndDate) + 1;
        $sportRecordsPerDay = intdiv($sportTotal, $sportDays);
        $sportLeftover = $sportTotal - ($sportRecordsPerDay * $sportDays);

        $usersWithGender = User::whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])
            ->select('id', 'gender')
            ->get();
        if ($usersWithGender->count() === 0) return;

        $sports = \App\Models\Sport::all();
        if ($sports->count() === 0) return;
        $sportsArr = $sports->toArray();

        $sportDate = $sportStartDate->copy();
        $sportUserIdx = 0;
        $batch = [];
        $insertedCount = 0;

        for ($d = 0; $d < $sportDays; $d++) {
            $recordsToday = $sportRecordsPerDay + ($sportLeftover > 0 ? 1 : 0);
            if ($sportLeftover > 0) $sportLeftover--;

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

                if (count($batch) >= $this->batchSize) {
                    $this->safeBatchInsert('histories', $batch);
                    $insertedCount += count($batch);
                    $batch = [];
                }
            }
            $sportDate->addDay();
        }

        if (count($batch)) {
            $this->safeBatchInsert('histories', $batch);
            $insertedCount += count($batch);
        }

        Log::info("Sport actions seeding completed: {$insertedCount} records");
    }

    /**
     * Seed education history data
     */
    private function seedEducationHistory(): void
    {
        Log::info('Starting education history seeding...');

        $educationTotal = 4944;
        $educationStartDate = Carbon::create(2024, 11, 1);
        $educationEndDate = Carbon::today();
        $educationDays = $educationStartDate->diffInDays($educationEndDate) + 1;
        $educationRecordsPerDay = intdiv($educationTotal, $educationDays);
        $educationLeftover = $educationTotal - ($educationRecordsPerDay * $educationDays);

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

        $educationDate = $educationStartDate->copy();
        $educationUserIdx = 0;
        $batch = [];
        $insertedCount = 0;

        for ($d = 0; $d < $educationDays; $d++) {
            $recordsToday = $educationRecordsPerDay + ($educationLeftover > 0 ? 1 : 0);
            if ($educationLeftover > 0) $educationLeftover--;

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

                if (count($batch) >= $this->batchSize) {
                    $this->safeBatchInsert('education_history_activities', $batch);
                    $insertedCount += count($batch);
                    $batch = [];
                }
            }
            $educationDate->addDay();
        }

        if (count($batch)) {
            $this->safeBatchInsert('education_history_activities', $batch);
            $insertedCount += count($batch);
        }

        Log::info("Education history seeding completed: {$insertedCount} records");
    }

    /**
     * Seed BMI data from CSV files
     */
    private function seedBMIData(): void
    {
        Log::info('Starting BMI data seeding...');

        $csvFiles = [
            ['file' => base_path('database/seeders/csv/pretest.csv'), 'category' => 'BMI', 'tgl_input' => '2024-11-15', 'type' => 'Pre-test'],
            ['file' => base_path('database/seeders/csv/post.csv'), 'category' => 'BMI', 'tgl_input' => '2025-01-29', 'type' => 'Post-test'],
        ];

        $userMap = User::whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])
            ->get()
            ->mapWithKeys(function ($u) {
                return [strtolower(trim($u->name)) => $u->id];
            });

        $totalInserted = 0;

        foreach ($csvFiles as $csvInfo) {
            if (!file_exists($csvInfo['file'])) {
                Log::warning("CSV file not found: {$csvInfo['file']}");
                continue;
            }

            $handle = fopen($csvInfo['file'], 'r');
            if (!$handle) {
                Log::error("Cannot open CSV file: {$csvInfo['file']}");
                continue;
            }

            $header = fgetcsv($handle, 0, ';');
            $peek = fgetcsv($handle, 0, ';');
            if (empty(array_filter($peek))) {
                $peek = fgetcsv($handle, 0, ';');
            } else {
                fseek($handle, -strlen(implode(';', $peek)) - 1, SEEK_CUR);
            }

            $batch = [];
            $matched = 0;
            $unmatched = [];
            $insertedCount = 0;

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
                    'name' => json_encode(['BMI ' . $csvInfo['type']]),
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

                if (count($batch) >= $this->batchSize) {
                    $this->safeBatchInsert('histories', $batch);
                    $insertedCount += count($batch);
                    $batch = [];
                }
            }

            if (count($batch)) {
                $this->safeBatchInsert('histories', $batch);
                $insertedCount += count($batch);
            }

            fclose($handle);
            $totalInserted += $insertedCount;

            Log::info("BMI Seeder: Matched users: {$matched}, Inserted: {$insertedCount} for file: {$csvInfo['file']}");
            if (!empty($unmatched)) {
                Log::info('BMI Seeder: Unmatched users: ' . implode(', ', array_slice($unmatched, 0, 10)) . (count($unmatched) > 10 ? '...' : ''));
            }
        }

        Log::info("BMI data seeding completed: {$totalInserted} records");
    }
}
