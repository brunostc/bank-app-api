<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pur_user_id');
            $table->unsignedBigInteger('pur_transaction_id');
            $table->bigInteger('pur_amount');
            $table->string('pur_description');
            $table->timestamps();

            $table->foreign('pur_user_id')->references('id')->on('users');
            $table->foreign('pur_transaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchases');
    }
}
