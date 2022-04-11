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
            $table->id()->startingValue(1000);
            $table->string('domain')->unique()->nullable();
            $table->string('admin_domain')->unique()->nullable();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('mobile')->nullable();
            $table->string('city')->nullable();
            $table->string('country');
            $table->string('business_type')->index()->nullable();
            $table->tinyInteger('is_setup_configured')->default(0);
            $table->tinyInteger('is_open')->default(0);
            $table->tinyInteger('status')->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
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
