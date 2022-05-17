<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tra_balance_id');
            $table->enum('tra_type', ['CHARGE', 'DEPOSIT']);
            $table->bigInteger('tra_previous_balance');
            $table->bigInteger('tra_new_balance');
            $table->bigInteger('tra_amount');
            $table->string('tra_description');
            $table->timestamps();

            $table->foreign('tra_balance_id')->references('id')->on('balances');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
