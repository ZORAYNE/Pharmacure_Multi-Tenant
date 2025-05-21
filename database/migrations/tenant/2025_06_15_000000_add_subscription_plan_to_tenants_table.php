<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionPlanToTenantsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('tenants') && !Schema::hasColumn('tenants', 'subscription_plan')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('subscription_plan')->default('basic')->after('status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('tenants') && Schema::hasColumn('tenants', 'subscription_plan')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->dropColumn('subscription_plan');
            });
        }
    }
}
