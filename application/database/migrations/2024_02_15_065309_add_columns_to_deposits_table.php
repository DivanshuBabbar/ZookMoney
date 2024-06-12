<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deposits', function (Blueprint $table) {
           $table->longtext('aggr_trnx');
           $table->text('ag_payment_timestamp', 200);
           $table->text('ag_internal_utr', 200);
           $table->text('ag_bank_reference_no', 200);
           $table->text('ag_payer_name', 200);
           $table->text('ag_payer_handle', 200);
           $table->text('ag_merchant_id', 200);
           $table->text('ag_payee_identifier', 200);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deposits', function (Blueprint $table) {
            //
        });
    }
}
