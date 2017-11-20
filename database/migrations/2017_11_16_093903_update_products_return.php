<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductsReturn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders_returns', function (Blueprint $table) {
            $table->integer('store_id')->default(0);
            $table->foreign('store_id')->references('id')->on('store')->onUpdate('NO ACTION')->onDelete('cascade');
        });
        Schema::table('orders_return_details', function (Blueprint $table) {
            $table->integer('unit_id')->default(0);
            $table->foreign('unit_id')->references('id')->on('unit')->onUpdate('NO ACTION')->onDelete('cascade');
        });
        Schema::table('returns', function (Blueprint $table) {
            $table->integer('store_id')->default(0);
            $table->foreign('store_id')->references('id')->on('store')->onUpdate('NO ACTION')->onDelete('cascade');
        });
        Schema::table('return_details', function (Blueprint $table) {
            $table->integer('unit_id')->default(0);
            $table->foreign('unit_id')->references('id')->on('unit')->onUpdate('NO ACTION')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
