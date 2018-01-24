<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePurchaseInvoicedetails extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		DB::statement('CREATE TABLE oldpurchase_invoice AS
                        SELECT *
                        FROM purchase_invoice ');
		Schema::table('purchase_invoice', function (Blueprint $table) {
				$table->dropColumn('store_id');
			});
		Schema::table('invoice_detailes', function (Blueprint $table) {
				$table->integer('store_id')->default(1);
				$table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
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
