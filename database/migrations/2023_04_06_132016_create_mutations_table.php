<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMutationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mutations', function (Blueprint $table) {
            $table->id();
            $table->string('trxid');
            $table->unsignedBigInteger('user_id');
            $table->string('note');
            $table->double('amount',20,2)->default(0);
            $table->double('balance',20,2)->default(0);
            $table->bigInteger('reference_id')->nullable();
            $table->string('reference_type')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->index(['trxid','user_id']);
            $table->index(['reference_id','reference_type','created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mutations');
    }
}
