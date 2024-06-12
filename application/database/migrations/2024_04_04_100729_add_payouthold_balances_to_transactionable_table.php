<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayoutholdBalancesToTransactionableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactionable', function (Blueprint $table) {
            $table->decimal('payout_hold_balance', 19, 2)->nullable()->default(NULL);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactionable', function (Blueprint $table) {
            $table->decimal('payout_hold_balance', 19, 2)->nullable()->default(NULL);
        });
    }
}
