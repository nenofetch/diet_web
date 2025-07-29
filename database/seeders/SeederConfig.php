<?php

namespace Database\Seeders;

class SeederConfig
{
    // Database configuration
    public const BATCH_SIZE = 1000;
    public const MAX_EXECUTION_TIME = 300; // 5 minutes
    public const MEMORY_LIMIT = 512 * 1024 * 1024; // 512MB

    // MySQL optimization settings (session-level only)
    public const MYSQL_SETTINGS = [
        'SET FOREIGN_KEY_CHECKS = 0',
        'SET AUTOCOMMIT = 0',
        'SET SESSION wait_timeout = 600',
        'SET SESSION innodb_flush_log_at_trx_commit = 2',
        'SET SESSION sync_binlog = 0',
    ];

    // Data seeding configuration
    public const SEEDING_CONFIG = [
        'main_foods' => [
            'total_records' => 44604,
            'categories' => ['Makan Pagi', 'Makan Siang', 'Makan Malam'],
            'start_date' => '2024-11-01',
            'end_date' => '2025-02-28',
        ],
        'snack_reports' => [
            'total_records' => 14616,
            'category' => 'Cemilan',
            'start_date' => '2024-11-01',
            'end_date' => '2025-02-28',
        ],
        'drink_reports' => [
            'total_records' => 55000,
            'category' => 'Minuman',
            'start_date' => '2024-11-01',
            'end_date' => '2025-02-28',
        ],
        'sport_actions' => [
            'total_records' => 1764,
            'category' => 'Olahraga',
            'start_date' => '2024-11-01',
            'end_date' => null, // Use current date
        ],
        'education_history' => [
            'total_records' => 4944,
            'start_date' => '2024-11-01',
            'end_date' => null, // Use current date
        ],
    ];

    // CSV file configuration
    public const CSV_FILES = [
        [
            'file' => 'database/seeders/csv/pretest.csv',
            'category' => 'BMI',
            'tgl_input' => '2024-11-15',
        ],
        [
            'file' => 'database/seeders/csv/post.csv',
            'category' => 'BMI',
            'tgl_input' => '2025-01-29',
        ],
    ];

    // Excluded users
    public const EXCLUDED_USERS = ['admin@gmail.com', 'user@gmail.com'];

    /**
     * Get MySQL optimization settings as array
     */
    public static function getMysqlSettings(): array
    {
        return self::MYSQL_SETTINGS;
    }

    /**
     * Get seeding configuration for specific type
     */
    public static function getSeedingConfig(string $type): array
    {
        return self::SEEDING_CONFIG[$type] ?? [];
    }

    /**
     * Get CSV files configuration
     */
    public static function getCsvFiles(): array
    {
        return self::CSV_FILES;
    }

    /**
     * Get excluded users
     */
    public static function getExcludedUsers(): array
    {
        return self::EXCLUDED_USERS;
    }
}
