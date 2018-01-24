<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnit extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		DB::statement('CREATE TABLE oldproducts AS
                        SELECT *
                        FROM products ');
		Schema::create('unit', function (Blueprint $table) {
				$table->increments('id');
				$table->string('title');
				$table->timestamps();
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('unit');
	}
}
