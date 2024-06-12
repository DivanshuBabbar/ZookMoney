<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentlinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentlinks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('paymentlink_id', 191)->nullable()->default(NULL);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
            $table->text('paymentlink_details')->nullable()->default(NULL);
            $table->decimal('amount', 16,8)->default(0.00);
            $table->string('email', 191)->nullable()->default(NULL);
            $table->boolean('is_crypto')->default(0);
            $table->boolean('payment_status')->default(0);
            $table->boolean('link_status')->default(0);
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
        Schema::dropIfExists('paymentlinks');
    }
}
