<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profits', function (Blueprint $table) {
            $table->id();
            $table->string('trxid')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('deposito_id');
            $table->double('amount',20,2);
            $table->decimal('percent',8,4);
            $table->double('profit',20,2);
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('deposito_id')->references('id')->on('depositos');
            $table->index(['trxid','user_id','deposito_id','created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profits');
    }
}
