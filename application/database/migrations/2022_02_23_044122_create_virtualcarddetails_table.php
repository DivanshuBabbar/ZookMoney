<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualcarddetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtualcarddetails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('virtualcard_id');
            $table->foreign('virtualcard_id')->references('id')->on('virtualcards')->onDelete('cascade')->onUpdate('cascade');
            $table->text('card_id');
            $table->text('hash');
            $table->text('pan')->nullable()->default(NULL);
            $table->text('masked_pan');
            $table->text('city')->nullable()->default(NULL);
            $table->text('state')->nullable()->default(NULL);
            $table->text('address')->nullable()->default(NULL);
            $table->text('address_2')->nullable()->default(NULL);;
            $table->bigInteger('account_id')->nullable()->default(NULL);
            $table->decimal('amount',9,2)->default(0.00);
            $table->string('name_on_card',191);
            $table->string('zip_code',191);
            $table->integer('cvv');
            $table->string('expiration',191);
            $table->text('card_created_at');
            $table->string('send_to',191)->nullable()->default(NULL);
            $table->string('bin_check_name',191)->nullable()->default(NULL);
            $table->string('card_type', 191)->nullable();
            $table->boolean('is_active')->default(0);
            $table->string('card_is_active', 191)->default('false');
            $table->text('callback_url')->nullable()->default(NULL);
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
        Schema::dropIfExists('virtualcarddetails');
    }
}
