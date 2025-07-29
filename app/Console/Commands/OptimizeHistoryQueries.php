<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\History;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeHistoryQueries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'history:optimize
                            {--category= : Specific category to analyze}
                            {--memory-test : Test memory usage with different query methods}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze and optimize history queries for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== History Query Optimization Analysis ===\n');

        // Check database indexes
        $this->checkIndexes();

        // Analyze data distribution
        $this->analyzeDataDistribution();

        // Memory usage test if requested
        if ($this->option('memory-test')) {
            $this->testMemoryUsage();
        }

        // Provide optimization recommendations
        $this->provideRecommendations();
    }

    /**
     * Check database indexes
     */
    private function checkIndexes()
    {
        $this->info('1. Checking Database Indexes...');

        $indexes = DB::select("
            SELECT
                TABLE_NAME,
                INDEX_NAME,
                COLUMN_NAME
            FROM INFORMATION_SCHEMA.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'histories'
            ORDER BY INDEX_NAME, SEQ_IN_INDEX
        ");

        $existingIndexes = collect($indexes)->groupBy('INDEX_NAME');

        $this->table(
            ['Index Name', 'Columns'],
            $existingIndexes->map(function ($index) {
                return [
                    $index->first()->INDEX_NAME,
                    $index->pluck('COLUMN_NAME')->implode(', ')
                ];
            })->toArray()
        );

        // Check for missing important indexes
        $importantIndexes = [
            'category' => 'histories_category_index',
            'user_id' => 'histories_user_id_index',
            'created_at' => 'histories_created_at_index',
            'tgl_input' => 'histories_tgl_input_index',
            'category_created_at' => 'histories_category_created_at_index'
        ];

        $missingIndexes = [];
        foreach ($importantIndexes as $column => $indexName) {
            if (!$existingIndexes->has($indexName)) {
                $missingIndexes[] = [$column, $indexName];
            }
        }

        if (!empty($missingIndexes)) {
            $this->warn('Missing important indexes:');
            $this->table(['Column', 'Suggested Index'], $missingIndexes);
        } else {
            $this->info('✓ All important indexes are present');
        }
    }

    /**
     * Analyze data distribution
     */
    private function analyzeDataDistribution()
    {
        $this->info('\n2. Analyzing Data Distribution...');

        $category = $this->option('category');

        if ($category) {
            $this->analyzeCategory($category);
        } else {
            $this->analyzeAllCategories();
        }
    }

    /**
     * Analyze specific category
     */
    private function analyzeCategory($category)
    {
        $total = History::where('category', $category)->count();
        $this->info("Category: {$category}");
        $this->info("Total records: " . number_format($total));

        // Date distribution
        $dateDistribution = History::where('category', $category)
            ->selectRaw('DATE(tgl_input) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(10)
            ->get();

        $this->info('Recent date distribution:');
        $this->table(
            ['Date', 'Count'],
            $dateDistribution->map(function ($item) {
                return [$item->date, number_format($item->count)];
            })->toArray()
        );
    }

    /**
     * Analyze all categories
     */
    private function analyzeAllCategories()
    {
        $categories = History::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        $this->table(
            ['Category', 'Count', 'Percentage'],
            $categories->map(function ($item) {
                $total = History::count();
                $percentage = $total > 0 ? round(($item->count / $total) * 100, 2) : 0;
                return [
                    $item->category,
                    number_format($item->count),
                    $percentage . '%'
                ];
            })->toArray()
        );
    }

    /**
     * Test memory usage with different query methods
     */
    private function testMemoryUsage()
    {
        $this->info('\n3. Testing Memory Usage...');

        $category = $this->option('category') ?: 'Cemilan';
        $this->info("Testing with category: {$category}");

        // Test 1: Get all records
        $this->testQueryMethod('get() - All records', function () use ($category) {
            return History::where('category', $category)->get();
        });

        // Test 2: Paginate
        $this->testQueryMethod('paginate(50)', function () use ($category) {
            return History::where('category', $category)->paginate(50);
        });

        // Test 3: Chunk
        $this->testQueryMethod('chunk(100)', function () use ($category) {
            $count = 0;
            History::where('category', $category)->chunk(100, function ($records) use (&$count) {
                $count += $records->count();
            });
            return $count;
        });

        // Test 4: Count only
        $this->testQueryMethod('count()', function () use ($category) {
            return History::where('category', $category)->count();
        });
    }

    /**
     * Test a specific query method
     */
    private function testQueryMethod($method, $callback)
    {
        $startMemory = memory_get_usage(true);
        $startTime = microtime(true);

        try {
            $result = $callback();
            $endTime = microtime(true);
            $endMemory = memory_get_usage(true);

            $executionTime = round(($endTime - $startTime) * 1000, 2);
            $memoryUsed = $endMemory - $startMemory;
            $memoryUsedMB = round($memoryUsed / 1024 / 1024, 2);

            $resultInfo = is_numeric($result) ? $result : (is_object($result) ? get_class($result) : 'N/A');

            $this->info("✓ {$method}: {$executionTime}ms, {$memoryUsedMB}MB, Result: {$resultInfo}");
        } catch (\Exception $e) {
            $this->error("✗ {$method}: " . $e->getMessage());
        }
    }

    /**
     * Provide optimization recommendations
     */
    private function provideRecommendations()
    {
        $this->info('\n4. Optimization Recommendations:');

        $recommendations = [
            'Use pagination instead of loading all records at once',
            'Add database indexes on frequently queried columns',
            'Use lazy loading for relationships',
            'Implement caching for frequently accessed data',
            'Consider using database views for complex queries',
            'Use chunk() for processing large datasets',
            'Optimize database queries with proper WHERE clauses',
            'Consider implementing database partitioning for very large tables'
        ];

        foreach ($recommendations as $index => $recommendation) {
            $this->line(($index + 1) . ". {$recommendation}");
        }

        $this->info('\n5. Quick Fixes:');
        $this->line('• Update controllers to use pagination');
        $this->line('• Add indexes: php artisan make:migration add_indexes_to_histories_table');
        $this->line('• Clear cache: php artisan cache:clear');
        $this->line('• Optimize autoloader: composer dump-autoload --optimize');
    }
}
