<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dep_user_id');
            $table->unsignedBigInteger('dep_file_id');
            $table->unsignedBigInteger('dep_transaction_id')->nullable();
            $table->bigInteger('dep_amount');
            $table->boolean('dep_is_approved')->default(false);
            $table->timestamp('dep_approved_at')->nullable();
            $table->timestamp('dep_reproved_at')->nullable();
            $table->timestamps();

            $table->foreign('dep_user_id')->references('id')->on('users');
            $table->foreign('dep_file_id')->references('id')->on('files');
            $table->foreign('dep_transaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deposits');
    }
}
