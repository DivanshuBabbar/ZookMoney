<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('payout_id')->nullable()->default(NULL);
            $table->string('transactionable_type',191);
            $table->integer('transaction_state_id');
            $table->decimal('gross', 19,8)->default(NULL);
            $table->decimal('fee', 16 , 8)->default(NULL);
            $table->decimal('net', 19 , 8)->default(NULL);
            $table->decimal('balance', 19,8)->nullable()->default(NULL);
            $table->string('money_flow',191);
            $table->string('currency_symbol')->nullable()->default(NULL);
            $table->unsignedBigInteger('currency_id');
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
        Schema::dropIfExists('payout');
    }
}
