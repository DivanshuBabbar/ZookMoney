<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInWithdrawalMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withdrawal_methods', function (Blueprint $table) {
            $table->tinyInteger('is_eligible')->after('status')->nullable()->default(1);
            $table->integer('sequence_no')->after('is_eligible')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('withdrawal_methods', function (Blueprint $table) {
            $table->dropColumn('is_eligible');
            $table->dropColumn('sequence_no');
        });
    }
}
