<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class TenantDatabaseService
{
    // Check if the database exists
    public function databaseExists($tenantName)
    {
        $databaseName = $this->getDatabaseName($tenantName);
        $databases = DB::select('SHOW DATABASES');

        foreach ($databases as $database) {
            if ($database->Database === $databaseName) {
                return true;
            }
        }

        return false;
    }

    // Create a new database for the tenant
    public function createDatabase(string $databaseName): void
    {
        \Log::info("Creating database: {$databaseName}");
        $charset = config('database.connections.mysql.charset', 'utf8mb4');
        $collation = config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');
        
        try {
            // Create the database if it does not exist
            $query = "CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET $charset COLLATE $collation";
            DB::statement($query);
            \Log::info("Database created: {$databaseName}");
    
            // Removed manual table creation to avoid conflicts with migrations
            // $this->createTables($databaseName);
        } catch (\Exception $e) {
            \Log::error("Failed to create database: " . $e->getMessage());
            throw new \Exception("Failed to create database: " . $e->getMessage());
        }
    }
    
    protected function createTables(string $databaseName): void
    {
        // Use the newly created database
        DB::statement("USE `{$databaseName}`");

        // Create users table
        DB::statement("
            CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) UNIQUE NOT NULL,
                `password` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ");

        // Create password_resets table
        DB::statement("
            CREATE TABLE IF NOT EXISTS `password_resets` (
                `email` VARCHAR(255) NOT NULL,
                `token` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Create sessions table
        DB::statement("
            CREATE TABLE IF NOT EXISTS `sessions` (
                `id` VARCHAR(255) PRIMARY KEY,
                `payload` TEXT NOT NULL,
                `last_activity` INTEGER NOT NULL,
                `user_id` INTEGER DEFAULT NULL,
                `ip_address` VARCHAR(45) DEFAULT NULL,
                `user_agent` VARCHAR(255) DEFAULT NULL
            )
        ");
    }

    public function runMigrations(string $databaseName): void
    {
        // Dynamically configure tenant database connection for migrations
        $tenantConnection = config('database.connections.mysql');
        $tenantConnection['database'] = $databaseName;
        Config::set("database.connections.tenant", $tenantConnection);
        DB::purge('tenant');

        try {
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => '/database/migrations/tenant', // Adjust this path as necessary
                '--force' => true,
            ]);
            \Log::info("Migrations run for database: {$databaseName}");
        } catch (\Exception $e) {
            \Log::error("Failed to run migrations: " . $e->getMessage());
            throw new \Exception("Failed to run migrations: " . $e->getMessage());
        }
    }

        // Helper method to get the database name
        private function getDatabaseName($tenantName)
        {
            // Sanitize tenant name: lowercase, replace spaces with underscores
            $sanitizedTenantName = strtolower(str_replace(' ', '_', $tenantName));
            return 'tenant_' . $sanitizedTenantName; // Adjust this as per your naming convention
        }
    
        // Set tenant connection
        public function setTenantConnection($tenantName)
        {
            // Fetch tenant from central db
            $tenant = Tenant::where('tenant_name', $tenantName)->firstOrFail();
    
            // Dynamically configure tenant database connection
            $tenantConnection = config('database.connections.mysql');
            $tenantConnection['database'] = $this->getDatabaseName($tenantName); // Ensure this is the correct field for the database name
            Config::set('database.connections.tenant', $tenantConnection);
    
            // Purge tenant connection so Laravel reconnects with new config
            DB::purge('tenant');
        }
    }
