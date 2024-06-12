<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFeeColumnsInMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->decimal('merchant_fixed_fee')->nullable()->after('description')->default(null);
            $table->decimal('merchant_percentage_fee')->nullable()->after('merchant_fixed_fee')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->dropColumn('merchant_fixed_fee');
            $table->dropColumn('merchant_percentage_fee');
        });
    }
}
