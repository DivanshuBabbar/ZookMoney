<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentlinkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paymentlinkorders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('paymentlink_id');
            $table->foreign('paymentlink_id')->references('id')->on('paymentlinks')->onDelete('cascade')->onUpdate('cascade');
            $table->string('paymentlink_reference', 191);
            $table->string('link_paid_by_platform', 191);
            $table->text('json_data')->nullable()->default(NULL);
            $table->string('email', 191)->nullable()->default(NULL);
            $table->boolean('order_state')->default(0);
            $table->string('order_status', 191)->default('unpaid');
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
        Schema::dropIfExists('paymentlinkorderss');
    }
}
