<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('cheq', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('bank_id')->unsigned();
		    $table->foreign('bank_id')->references('id')->on('bank')->onDelete('cascade');
		    $table->integer('supplier_id')->unsigned();
		    $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
		    $table->string('cheq_num');
		    $table->double('value');
		    $table->date('date')->nullable();
		    $table->boolean('auto');
		    $table->boolean('is_paid')->default(0);
		    $table->string('note')->nullable();
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
	    Schema::dropIfExists('cheq');
    }
}
