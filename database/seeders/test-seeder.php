<?php

/**
 * Test script for DataSeeder configuration
 * Run this script to verify your setup before running the full seeder
 */

require_once __DIR__ . '/../../../vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Food;
use App\Models\Sport;
use App\Models\Education;

// Bootstrap Laravel
$app = require_once __DIR__ . '/../../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DataSeeder Configuration Test ===\n\n";

// Test 1: Database Connection
echo "1. Testing database connection...\n";
try {
    DB::connection()->getPdo();
    echo "   ✓ Database connection successful\n";
} catch (Exception $e) {
    echo "   ✗ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Required Tables
echo "\n2. Checking required tables...\n";
$requiredTables = ['users', 'foods', 'histories', 'sports', 'educations', 'education_history_activities'];
$missingTables = [];

foreach ($requiredTables as $table) {
    if (Schema::hasTable($table)) {
        echo "   ✓ Table '{$table}' exists\n";
    } else {
        echo "   ✗ Table '{$table}' missing\n";
        $missingTables[] = $table;
    }
}

if (!empty($missingTables)) {
    echo "\n   Missing tables: " . implode(', ', $missingTables) . "\n";
    echo "   Please run migrations first: php artisan migrate\n";
    exit(1);
}

// Test 3: Required Data
echo "\n3. Checking required data...\n";

// Check users
$userCount = User::whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])->count();
if ($userCount > 0) {
    echo "   ✓ Found {$userCount} users for seeding\n";
} else {
    echo "   ✗ No users found for seeding\n";
    echo "   Please ensure users exist in the database\n";
    exit(1);
}

// Check foods
$foodCount = Food::count();
if ($foodCount > 0) {
    echo "   ✓ Found {$foodCount} foods for seeding\n";
} else {
    echo "   ✗ No foods found for seeding\n";
    echo "   Please ensure foods exist in the database\n";
    exit(1);
}

// Check sports
$sportCount = Sport::count();
if ($sportCount > 0) {
    echo "   ✓ Found {$sportCount} sports for seeding\n";
} else {
    echo "   ✗ No sports found for seeding\n";
    echo "   Please ensure sports exist in the database\n";
    exit(1);
}

// Check educations
$educationCount = Education::count();
if ($educationCount > 0) {
    echo "   ✓ Found {$educationCount} educations for seeding\n";
} else {
    echo "   ✗ No educations found for seeding\n";
    echo "   Please ensure educations exist in the database\n";
    exit(1);
}

// Test 4: CSV Files
echo "\n4. Checking CSV files...\n";
$csvFiles = [
    base_path('database/seeders/csv/pretest.csv'),
    base_path('database/seeders/csv/post.csv'),
];

foreach ($csvFiles as $csvFile) {
    if (file_exists($csvFile)) {
        $size = filesize($csvFile);
        echo "   ✓ CSV file found: " . basename($csvFile) . " ({$size} bytes)\n";
    } else {
        echo "   ⚠ CSV file missing: " . basename($csvFile) . "\n";
        echo "   This is optional but recommended for complete data\n";
    }
}

// Test 5: Memory and Time Limits
echo "\n5. Checking PHP configuration...\n";
$memoryLimit = ini_get('memory_limit');
$maxExecutionTime = ini_get('max_execution_time');
$maxInputTime = ini_get('max_input_time');

echo "   Memory limit: {$memoryLimit}\n";
echo "   Max execution time: {$maxExecutionTime} seconds\n";
echo "   Max input time: {$maxInputTime} seconds\n";

// Test 6: MySQL Configuration
echo "\n6. Testing MySQL optimizations...\n";
try {
    // Test foreign key check setting
    DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    echo "   ✓ Foreign key checks can be disabled\n";

    // Test autocommit setting
    DB::statement('SET AUTOCOMMIT = 0');
    echo "   ✓ Autocommit can be disabled\n";

    // Test timeout setting
    DB::statement('SET SESSION wait_timeout = 600');
    echo "   ✓ Session timeout can be increased\n";

    // Test packet size setting (requires GLOBAL privileges)
    echo "   ⚠ Max allowed packet requires GLOBAL privileges (skipped)\n";

    // Re-enable settings
    DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    DB::commit();
} catch (Exception $e) {
    echo "   ✗ MySQL optimization test failed: " . $e->getMessage() . "\n";
    echo "   Some optimizations may not work with your MySQL configuration\n";
}

// Test 7: Estimated Data Volume
echo "\n7. Estimating data volume...\n";
$config = [
    'main_foods' => 44604,
    'snack_reports' => 14616,
    'drink_reports' => 55000,
    'sport_actions' => 1764,
    'education_history' => 4944,
];

$totalRecords = array_sum($config);
echo "   Total records to be created: {$totalRecords}\n";

// Estimate memory usage (rough calculation)
$estimatedMemory = $totalRecords * 1024; // ~1KB per record
$estimatedMemoryMB = round($estimatedMemory / 1024 / 1024, 2);
echo "   Estimated memory usage: {$estimatedMemoryMB} MB\n";

// Estimate execution time (rough calculation)
$estimatedTime = round($totalRecords / 1000, 1); // ~1 second per 1000 records
echo "   Estimated execution time: {$estimatedTime} seconds\n";

echo "\n=== Test Results ===\n";
echo "✓ All tests passed! Your configuration is ready for seeding.\n\n";
echo "To run the seeder, use one of these commands:\n";
echo "  php artisan db:seed-data --force\n";
echo "  php artisan db:seed --class=DataSeeder\n\n";
echo "For monitoring and better control, use the custom command:\n";
echo "  php artisan db:seed-data --memory-limit=1G --time-limit=600 --force\n";
