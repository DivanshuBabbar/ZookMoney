<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VorderidInVoucherOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voucherorders', function (Blueprint $table) {
            $table->text('vorderid')->nullable()->default(NULL);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voucherorders', function (Blueprint $table) {
            $table->dropColumn('vorderid');
        });
    }
}
