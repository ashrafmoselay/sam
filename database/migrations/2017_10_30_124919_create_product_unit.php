<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_store_unit', function (Blueprint $table) {
            $table->increments('id'); 
            $table->double('pieces_num');
            $table->integer('unit_id')->unsigned();
            $table->foreign('unit_id')->references('id')->on('unit')->onDelete('cascade');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->double('cost_price');
            $table->double('sale_price');
            $table->boolean('default_sale')->default(0);
            $table->boolean('default_purchase')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_store_unit');
    }
}
