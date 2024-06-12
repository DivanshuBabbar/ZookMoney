<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->decimal('payout_fixed_fee', 16, 8)->nullable()->default(0);
            $table->decimal('payout_percentage_fee', 16, 8)->nullable()->default(0);
            $table->decimal('wire_transfer_fixed_fee', 16, 8)->nullable()->default(0);
            $table->decimal('wire_transfer_percentage_fee', 16, 8)->nullable()->default(0);

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
            //
        });
    }
}
