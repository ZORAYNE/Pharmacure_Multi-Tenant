<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ListTenantTables extends Command
{
    protected $signature = 'tenant:show-tables';

    protected $description = 'List all tables in the tenant database connection';

    public function handle()
    {
        $tables = DB::connection('tenant')->select('SHOW TABLES');
        $database = DB::connection('tenant')->getDatabaseName();

        $this->info("Tables in tenant database: {$database}");
        foreach ($tables as $table) {
            $tableName = array_values((array) $table)[0];
            $this->line($tableName);
        }

        return 0;
    }
}
