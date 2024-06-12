<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInDepositMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deposit_methods', function (Blueprint $table) {
            $table->tinyInteger('is_eligible')->after('status')->default(1)->nullable();
            $table->integer('sequence_no')->nullable()->after('is_eligible');
            $table->string('transaction_receipt_ref_no_format')->nullable()->after('sequence_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deposit_methods', function (Blueprint $table) {
            $table->dropColumn('is_eligible');
            $table->dropColumn('sequence_no');
            $table->dropColumn('transaction_receipt_ref_no_format');
        });
    }
}
