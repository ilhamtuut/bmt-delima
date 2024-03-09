<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInDepositosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('depositos', function (Blueprint $table) {
            $table->string('type_deposito')->after('status')->default('regular');
            $table->integer('contract')->after('type_deposito')->default(0);
            $table->decimal('percent',8,4)->after('contract')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('depositos', function (Blueprint $table) {
            //
        });
    }
}
