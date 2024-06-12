<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIpnUrlToPaymentlinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paymentlinks', function (Blueprint $table) {
            $table->text('ipn_url')->nullable()->default(NULL);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paymentlinks', function (Blueprint $table) {
            $table->dropColumn('ipn_url');
        });
    }
}
