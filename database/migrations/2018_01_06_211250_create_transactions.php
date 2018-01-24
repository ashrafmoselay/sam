<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('transactions', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('bank_id')->unsigned();
			$table->foreign('bank_id')->references('id')->on('bank')->onDelete('cascade');
			$table->date('op_date');
			$table->double('value');
			$table->double('total');
			$table->double('due');
			$table->integer('type');
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
		Schema::dropIfExists('transactions');
	}
}
