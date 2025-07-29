# Optimized DataSeeder

This directory contains an optimized version of the DataSeeder for better MySQL performance and reliability.

## Features

-   **Database Transaction Management**: Uses transactions to ensure data consistency
-   **Memory Management**: Automatic garbage collection and memory monitoring
-   **Error Handling**: Comprehensive error handling with detailed logging
-   **Batch Processing**: Optimized batch sizes for better performance
-   **Progress Tracking**: Real-time progress monitoring and logging
-   **MySQL Optimizations**: Database-specific optimizations for better performance

## Files

-   `DataSeeder.php` - Main optimized seeder class
-   `SeederConfig.php` - Configuration class for seeder settings
-   `README.md` - This documentation file

## Usage

### Method 1: Using the Custom Command (Recommended)

```bash
# Run with confirmation prompt
php artisan db:seed-data

# Run without confirmation
php artisan db:seed-data --force

# Run with custom memory and time limits
php artisan db:seed-data --memory-limit=1G --time-limit=600 --force
```

### Method 2: Using Laravel's Default Seeder

```bash
# Run the seeder directly
php artisan db:seed --class=DataSeeder

# Or run all seeders
php artisan db:seed
```

### Method 3: Programmatically

```php
use Database\Seeders\DataSeeder;

$seeder = new DataSeeder();
$seeder->run();
```

## Configuration

You can modify the seeder behavior by editing the `SeederConfig.php` file:

```php
// Batch size for database inserts
public const BATCH_SIZE = 1000;

// Maximum execution time in seconds
public const MAX_EXECUTION_TIME = 300;

// Memory limit in bytes
public const MEMORY_LIMIT = 512 * 1024 * 1024; // 512MB
```

## MySQL Optimizations

The seeder automatically applies the following MySQL optimizations:

-   Disables foreign key checks during seeding
-   Disables autocommit for better performance
-   Increases wait timeout to 600 seconds
-   Optimizes InnoDB settings for bulk inserts
-   Note: max_allowed_packet requires GLOBAL privileges and is not set automatically

## Troubleshooting

### Common Issues

#### 1. Memory Exhaustion

**Error**: `Fatal error: Allowed memory size exhausted`

**Solution**:

```bash
# Increase memory limit
php artisan db:seed-data --memory-limit=1G
```

#### 2. Timeout Issues

**Error**: `Maximum execution time exceeded`

**Solution**:

```bash
# Increase time limit
php artisan db:seed-data --time-limit=600
```

#### 3. Database Connection Issues

**Error**: `SQLSTATE[HY000] [2002] Connection refused`

**Solution**:

-   Check database configuration in `.env`
-   Ensure MySQL server is running
-   Verify database credentials

#### 4. Foreign Key Constraint Errors

**Error**: `Cannot add or update a child row: a foreign key constraint fails`

**Solution**:

-   Ensure all referenced data exists (users, foods, sports, etc.)
-   Check the order of seeding operations

#### 5. CSV File Not Found

**Warning**: `CSV file not found`

**Solution**:

-   Ensure CSV files exist in `database/seeders/csv/`
-   Check file permissions
-   Verify file paths in `SeederConfig.php`

### Performance Tips

1. **Increase Batch Size**: For better performance, increase `BATCH_SIZE` in `SeederConfig.php`
2. **Use SSD Storage**: Ensure your database is on SSD storage for better I/O performance
3. **Optimize MySQL Configuration**: Adjust MySQL settings for bulk operations
4. **Monitor Resources**: Use the custom command to monitor memory and execution time

### Logging

The seeder provides detailed logging. Check your Laravel logs for:

-   Progress updates
-   Error messages
-   Performance metrics
-   Data validation results

Log files are typically located in `storage/logs/laravel.log`.

## Data Structure

The seeder creates the following types of data:

1. **Main Foods** (44,604 records)

    - Categories: Makan Pagi, Makan Siang, Makan Malam
    - Date range: 2024-11-01 to 2025-02-28

2. **Snack Reports** (14,616 records)

    - Category: Cemilan
    - Date range: 2024-11-01 to 2025-02-28

3. **Drink Reports** (55,000 records)

    - Category: Minuman
    - Date range: 2024-11-01 to 2025-02-28

4. **Sport Actions** (1,764 records)

    - Category: Olahraga
    - Date range: 2024-11-01 to current date

5. **Education History** (4,944 records)

    - Date range: 2024-11-01 to current date

6. **BMI Data** (from CSV files)
    - Pretest data: 2024-11-15
    - Posttest data: 2025-02-15

## Requirements

-   Laravel 8+
-   MySQL 5.7+ or MariaDB 10.2+
-   PHP 8.0+
-   Sufficient memory (minimum 512MB recommended)
-   CSV files in `database/seeders/csv/` directory

## MySQL Privileges

The seeder uses session-level MySQL settings that don't require special privileges. However, if you want to optimize performance further, you can manually set these global variables (requires SUPER privilege):

```sql
-- Set these manually if you have SUPER privileges
SET GLOBAL max_allowed_packet = 67108864; -- 64MB
SET GLOBAL innodb_buffer_pool_size = 1073741824; -- 1GB
SET GLOBAL innodb_log_file_size = 268435456; -- 256MB
```

## Safety Features

-   **Transaction Rollback**: Automatic rollback on errors
-   **Data Validation**: Checks for required data before seeding
-   **Progress Monitoring**: Real-time progress tracking
-   **Error Recovery**: Detailed error reporting and logging
-   **Resource Management**: Automatic memory and time management
