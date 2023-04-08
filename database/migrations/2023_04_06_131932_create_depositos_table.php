<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depositos', function (Blueprint $table) {
            $table->id();
            $table->string('trxid')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('deposito_type_id');
            $table->double('amount',20,2)->default(0);
            $table->integer('code')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('deposito_type_id')->references('id')->on('deposito_types');
            $table->index(['trxid','user_id','deposito_type_id','status','created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('depositos');
    }
}
