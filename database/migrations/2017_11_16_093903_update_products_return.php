<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsReturn extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('orders_returns', function (Blueprint $table) {
				$table->integer('store_id')->default(1);
				$table->foreign('store_id')->references('id')->on('store')->onUpdate('NO ACTION')->onDelete('cascade');
			});
		Schema::table('orders_return_details', function (Blueprint $table) {
				$table->integer('unit_id')->default(1);
				$table->foreign('unit_id')->references('id')->on('unit')->onUpdate('NO ACTION')->onDelete('cascade');
			});
		Schema::table('returns', function (Blueprint $table) {
				$table->integer('store_id')->default(1);
				$table->foreign('store_id')->references('id')->on('store')->onUpdate('NO ACTION')->onDelete('cascade');
			});
		Schema::table('return_details', function (Blueprint $table) {
				$table->integer('unit_id')->default(1);
				$table->foreign('unit_id')->references('id')->on('unit')->onUpdate('NO ACTION')->onDelete('cascade');
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		//
	}
}
