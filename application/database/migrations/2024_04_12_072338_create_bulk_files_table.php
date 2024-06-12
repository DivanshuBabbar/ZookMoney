<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('type');
            $table->string('path');
            $table->timestamp('data_time_schedular'); 
            $table->string('request_id')->nullable();
            $table->decimal('total_payout', 10, 2)->nullable(); 
            $table->decimal('total_amount', 10, 2)->nullable(); 
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
            $table->string('response_file')->nullable();
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
        Schema::dropIfExists('bulk_files');
    }
}
