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
            $table->string('domain')->nullable()->unique('domain');
            $table->string('admin_domain')->nullable()->unique();
            $table->string('slug');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile');
            $table->string('city');
            $table->string('country');
            $table->string('business_type');
            $table->tinyInteger('is_setup_configured')->default(0);
            $table->tinyInteger('status');
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
