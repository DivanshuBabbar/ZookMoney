<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucherorders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('voucher_id')->nullable()->default(NULL);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('currency_id');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount', 16,8)->default(0.00);
            $table->decimal('fee', 16,8)->default(0.00);
            $table->decimal('total', 16,8)->default(0.00);
            $table->boolean('is_crypto')->default(0);
            $table->string('order_status',191)->default('active');
            $table->string('payment_method',191)->nullable()->default(NULL);
            $table->text('out_transaction_id',191);
            $table->integer('state')->default(1);
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
        Schema::dropIfExists('voucherorders');
    }
}
