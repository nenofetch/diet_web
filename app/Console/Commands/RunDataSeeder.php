<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\DataSeeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Exception;

class RunDataSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:seed-data
                            {--force : Force run without confirmation}
                            {--memory-limit=512M : Memory limit for the process}
                            {--time-limit=300 : Time limit in seconds}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the optimized DataSeeder with progress tracking and error handling';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting optimized DataSeeder...');

        // Set memory and time limits
        $memoryLimit = $this->option('memory-limit');
        $timeLimit = $this->option('time-limit');

        ini_set('memory_limit', $memoryLimit);
        set_time_limit($timeLimit);

        // Check if force option is used or ask for confirmation
        if (!$this->option('force')) {
            if (!$this->confirm('This will seed a large amount of data. Are you sure you want to continue?')) {
                $this->info('Seeding cancelled.');
                return 0;
            }
        }

        // Check database connection
        try {
            DB::connection()->getPdo();
            $this->info('Database connection: OK');
        } catch (Exception $e) {
            $this->error('Database connection failed: ' . $e->getMessage());
            return 1;
        }

        // Check required tables
        $requiredTables = ['users', 'foods', 'histories', 'sports', 'educations', 'education_history_activities'];
        foreach ($requiredTables as $table) {
            if (!Schema::hasTable($table)) {
                $this->error("Required table '{$table}' does not exist.");
                return 1;
            }
        }
        $this->info('Required tables: OK');

        // Check for required data
        $userCount = \App\Models\User::whereNotIn('email', ['admin@gmail.com', 'user@gmail.com'])->count();
        if ($userCount === 0) {
            $this->error('No users found for seeding. Please ensure users exist in the database.');
            return 1;
        }
        $this->info("Found {$userCount} users for seeding");

        $foodCount = \App\Models\Food::count();
        if ($foodCount === 0) {
            $this->error('No foods found for seeding. Please ensure foods exist in the database.');
            return 1;
        }
        $this->info("Found {$foodCount} foods for seeding");

        // Check CSV files
        $csvFiles = [
            base_path('database/seeders/csv/pretest.csv'),
            base_path('database/seeders/csv/post.csv'),
        ];

        foreach ($csvFiles as $csvFile) {
            if (!file_exists($csvFile)) {
                $this->warn("CSV file not found: {$csvFile}");
            } else {
                $this->info("CSV file found: " . basename($csvFile));
            }
        }

        // Start seeding with progress bar
        $this->info('Starting data seeding process...');
        $startTime = microtime(true);

        try {
            $seeder = new DataSeeder();
            $seeder->run();

            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            $this->info("DataSeeder completed successfully in {$executionTime} seconds");
            $this->info('Memory usage: ' . $this->formatBytes(memory_get_peak_usage(true)));

            // Show summary
            $this->showSeedingSummary();

            return 0;
        } catch (Exception $e) {
            $this->error('DataSeeder failed: ' . $e->getMessage());
            Log::error('DataSeeder failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'memory_usage' => memory_get_peak_usage(true),
            ]);
            return 1;
        }
    }

    /**
     * Show seeding summary
     */
    private function showSeedingSummary(): void
    {
        $this->newLine();
        $this->info('=== Seeding Summary ===');

        $historyCount = \App\Models\History::count();
        $educationHistoryCount = \App\Models\EducationHistoryActivity::count();

        $this->table(
            ['Table', 'Records'],
            [
                ['histories', $historyCount],
                ['education_history_activities', $educationHistoryCount],
            ]
        );

        // Show breakdown by category
        $categories = \App\Models\History::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        if ($categories->count() > 0) {
            $this->newLine();
            $this->info('Records by category:');
            $categoryData = $categories->map(function ($item) {
                return [$item->category, $item->count];
            })->toArray();

            $this->table(['Category', 'Count'], $categoryData);
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
