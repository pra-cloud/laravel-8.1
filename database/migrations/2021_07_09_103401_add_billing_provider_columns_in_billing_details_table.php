<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingProviderColumnsInBillingDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('tenant_billing_details', function (Blueprint $table) {
            $table->string('billing_provider')->nullable();
            $table->string('billing_provider_customer_id', 191)->nullable();
        });
    }

    public function down()
    {
        Schema::table('tenant_billing_details', function (Blueprint $table) {
            $table->dropColumn('billing_provider');
            $table->dropColumn('billing_provider_customer_id');
        });
    }
}
