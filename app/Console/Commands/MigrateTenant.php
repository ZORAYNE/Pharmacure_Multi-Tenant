<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class MigrateTenant extends Command
{
    protected $signature = 'tenant:migrate {tenant}';

    protected $description = 'Run migrations for a specific tenant database';

    public function handle()
    {
        $tenantName = $this->argument('tenant');

        $tenant = Tenant::where('tenant_name', $tenantName)->first();

        if (!$tenant) {
            $this->error("Tenant '{$tenantName}' not found.");
            return 1;
        }

        $conn = config('database.connections.mysql');
        $conn['database'] = $tenant->tenant_name;
        Config::set('database.connections.tenant', $conn);
        DB::purge('tenant');

        $this->info("Running migrations for tenant database: {$tenant->tenant_name}");

        // Run migrations on tenant connection
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        $this->info(Artisan::output());

        return 0;
    }
}
