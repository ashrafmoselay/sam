<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->nullable();
            $table->integer('category2_id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->integer('quantity');
            $table->integer('sale_count')->default(0);
            $table->decimal('price');
            $table->decimal('price2')->nulllable();
            $table->decimal('price3')->nulllable();
            $table->decimal('cost');
            $table->decimal('last_avg_cost')->default(0);
            $table->string('code')->nulllable();
            $table->string('model')->nulllable();
            $table->timestamps();
            $table->decimal('observe')->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
