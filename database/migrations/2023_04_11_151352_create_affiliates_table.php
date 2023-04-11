<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('trxid')->unique();
            $table->unsignedBigInteger('deposito_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('from_id');
            $table->double('amount',20,2)->default(0);
            $table->decimal('percent',8,4)->default(0);
            $table->double('bonus',20,2)->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('deposito_id')->references('id')->on('depositos');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('from_id')->references('id')->on('users');
            $table->index(['trxid','user_id','status','created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliates');
    }
}
