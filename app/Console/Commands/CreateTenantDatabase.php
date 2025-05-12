<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class CreateTenantDatabase extends Command
{
    protected $signature = 'tenant:create-database';

    protected $description = 'Create the tenant database if it does not exist';

    public function handle()
    {
        $tenantConnection = config('database.connections.tenant');

        $databaseName = $tenantConnection['database'] ?? null;

        if (!$databaseName) {
            $this->error('Tenant database name is not configured.');
            return 1;
        }

        // Use the default connection to create the tenant database
        $defaultConnection = config('database.connections.mysql');

        $charset = $defaultConnection['charset'] ?? 'utf8mb4';
        $collation = $defaultConnection['collation'] ?? 'utf8mb4_unicode_ci';

        // Temporarily set database to null to connect to MySQL server
        $tempConnection = $defaultConnection;
        $tempConnection['database'] = null;

        Config::set('database.connections.temp_mysql', $tempConnection);
        DB::purge('temp_mysql');

        try {
            DB::connection('temp_mysql')->statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET {$charset} COLLATE {$collation}");
            $this->info("Database '{$databaseName}' created or already exists.");
        } catch (\Exception $e) {
            $this->error("Failed to create database '{$databaseName}': " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
