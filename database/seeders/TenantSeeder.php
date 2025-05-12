<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\TenantDatabaseService;

class TenantSeeder extends Seeder
{
    protected $tenantDatabaseService;

    public function __construct(TenantDatabaseService $tenantDatabaseService)
    {
        $this->tenantDatabaseService = $tenantDatabaseService;
    }

    public function run()
    {
        $tenantNames = [];

        for ($i = 1; $i <= 5; $i++) {
            $tenantName = 'Tenant ' . $i;
            
            // Ensure unique tenant name
            while (in_array($tenantName, $tenantNames)) {
                $tenantName .= ' ' . Str::random(3); // Append random string
            }
            $tenantNames[] = $tenantName;

            // Check if tenant already exists, skip if yes
            $existingTenant = DB::table('tenants')->where('tenant_name', $tenantName)->first();
            if ($existingTenant) {
                continue;
            }

            // Create tenant
            $tenantId = DB::table('tenants')->insertGetId([
                'tenant_name' => $tenantName,
                'full_name' => 'Full Name ' . $i,
                'email' => 'tenant' . $i . '@example.com',
                'password' => bcrypt('password'),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create tenant database if not exists
            // Use public method databaseExists and createDatabase, but getDatabaseName is private, so replicate logic here
            $sanitizedTenantName = strtolower(str_replace(' ', '_', $tenantName));
            $databaseName = 'tenant_' . $sanitizedTenantName;
            if (!$this->tenantDatabaseService->databaseExists($tenantName)) {
                $this->tenantDatabaseService->createDatabase($databaseName);
                $this->tenantDatabaseService->runMigrations($databaseName);
            }

            // Set tenant connection dynamically
            $this->tenantDatabaseService->setTenantConnection($tenantName);

            // Create an admin user for the tenant
            DB::connection('tenant')->table('users')->insert([
                'name' => 'Admin ' . $i,
                'email' => 'admin' . $i . '@example.com',
                'role' => 'admin',
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create 5 pharmacist users for the tenant
            for ($j = 1; $j <= 5; $j++) {
                DB::connection('tenant')->table('users')->insert([
                    'name' => 'Pharmacist ' . $j . ' of Tenant ' . $i,
                    'email' => 'pharmacist' . $j . 'tenant' . $i . '@example.com',
                    'role' => 'pharmacist',
                    'password' => bcrypt('password'),
                    'remember_token' => Str::random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
