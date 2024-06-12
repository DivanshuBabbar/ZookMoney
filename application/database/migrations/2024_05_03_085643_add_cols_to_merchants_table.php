<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->enum('time_status', ['0','24','48','72'])->default('24');
            $table->decimal('min_payin', 16, 2)->nullable();
            $table->decimal('max_payin', 16, 2)->nullable();
            $table->decimal('min_payout', 16, 2)->nullable();
            $table->decimal('max_payout', 16, 2)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchants', function (Blueprint $table) {
            //
        });
    }
}
