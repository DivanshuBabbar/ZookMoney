<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('white_label_status')->default(1);
            $table->tinyInteger('wire_transfer_status')->default(1);
            $table->tinyInteger('payout_status')->default(1);
            $table->tinyInteger('payin_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('white_label_status');
            $table->dropColumn('wire_transfer_status');
            $table->dropColumn('payout_status');
            $table->dropColumn('payin_status');
        });
    }
}
