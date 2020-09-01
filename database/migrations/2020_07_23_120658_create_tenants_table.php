<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('domain', 45)->unique('domain');
            $table->string('name', 45);
            $table->string('email', 45);
            $table->string('mobile', 45);
            $table->string('city', 45);
            $table->string('country', 45);
            $table->tinyInteger('status');
            $table->foreignId('saas_plan_id');
            $table->string('plan_billing_cycle', 10);
            $table->date('plan_expiry_date');
            $table->tinyInteger('payment_failed_tries')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
