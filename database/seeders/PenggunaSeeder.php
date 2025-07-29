<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Process female users (perempuan)
        $perempuan = fopen(base_path('database/seeders/csv/perempuan.csv'), 'r');
        $header = fgetcsv($perempuan, 0, ';'); // Skip header

        while (($row = fgetcsv($perempuan, 0, ';')) !== false) {
            if (count($row) >= 3) {
                $name = trim($row[0]);
                $age = (int) $row[2];

                // Calculate date of birth based on age
                $dateOfBirth = $this->calculateDateOfBirth($age);

                // Generate email from name with date of birth for uniqueness
                $email = $this->generateEmail($name, $dateOfBirth);

                // Create user
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt('12345678'),
                    'gender' => 'Perempuan',
                    'date_of_birth' => $dateOfBirth,
                    'work' => 'Pelajar',
                ]);

                $user->assignRole('user');
            }
        }
        fclose($perempuan);

        // Process male users (laki-laki)
        $laki = fopen(base_path('database/seeders/csv/laki-laki.csv'), 'r');
        $header = fgetcsv($laki, 0, ';'); // Skip header

        while (($row = fgetcsv($laki, 0, ';')) !== false) {
            if (count($row) >= 3) {
                $name = trim($row[0]);
                $age = (int) $row[2];

                // Calculate date of birth based on age
                $dateOfBirth = $this->calculateDateOfBirth($age);

                // Generate email from name with date of birth for uniqueness
                $email = $this->generateEmail($name, $dateOfBirth);

                // Create user
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt('12345678'),
                    'gender' => 'Laki-laki',
                    'date_of_birth' => $dateOfBirth,
                    'work' => 'Pelajar',
                ]);

                $user->assignRole('user');
            }
        }
        fclose($laki);
    }

    /**
     * Generate email from name with date of birth for uniqueness
     */
    private function generateEmail($name, $dateOfBirth)
    {
        // Clean the name and convert to lowercase
        $cleanName = strtolower(trim($name));

        // Remove special characters and extra spaces
        $cleanName = preg_replace('/[^a-z\s]/', '', $cleanName);
        $cleanName = preg_replace('/\s+/', ' ', $cleanName);

        // Split into words
        $words = explode(' ', $cleanName);

        // Take first two words if available, otherwise use what we have
        if (count($words) >= 2) {
            $emailName = $words[0] . $words[1];
        } else {
            $emailName = $words[0];
        }

        // Remove any remaining spaces
        $emailName = str_replace(' ', '', $emailName);

        // Extract year from date of birth for uniqueness
        $birthYear = Carbon::parse($dateOfBirth)->format('Y');

        // Add birth year to make email unique
        $emailName = $emailName . $birthYear;

        $randomWord = Str::random(3);

        return $emailName . $randomWord . '@gmail.com';
    }

    /**
     * Calculate date of birth based on age
     */
    private function calculateDateOfBirth($age)
    {
        // Calculate birth year (current year - age)
        $currentYear = Carbon::now()->year;
        $birthYear = $currentYear - $age;

        // Use a random month and day for variety
        $month = rand(1, 12);
        $day = rand(1, 28); // Using 28 to avoid issues with February

        return Carbon::create($birthYear, $month, $day)->format('Y-m-d');
    }
}
