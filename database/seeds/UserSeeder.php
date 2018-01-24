<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		DB::table('users')->truncate();
		DB::table('roles')->truncate();
		DB::table('role_user')->truncate();
		DB::table('unit')->truncate();
		$list = [
			'name'           => "Admin",
			'email'          => "admin@admin.com",
			'password'       => bcrypt(123456),
			'role'           => 1,
			'remember_token' => str_random(10)
		];
		DB::table('users')->insert($list);

		$list = [
			[
				'name'         => "admin",
				'display_name' => "مسئول",
			],
			[
				'name'         => "user",
				'display_name' => "مستخدم",
			],
		];
		DB::table('roles')->insert($list);
		DB::table('role_user')->insert(['user_id' => 1, 'role_id' => 1]);
		DB::table('store')->insert(['address' => 'المعرض','mobile'=>'','note'=>'']);
		\App\Permission::getRoutePermission(true);
		DB::table('unit')->insert(
			array('title' => 'قطعة')
		);
		$products = DB::table('oldproducts')->get();
		$store    = DB::table('store')->first();
		$purList  = DB::table('oldpurchase_invoice')->get();
		foreach ($purList as $item) {
			DB::table('invoice_detailes')->where('invoice_id', $item->id)->update(
				array('store_id' => $item->store_id)
			);
		}

		DB::table('products_store')->update(
			array('unit_id' => 1)
		);
		DB::table('order_detailes')->update(
			array('unit_id' => 1)
		);
		DB::table('invoice_detailes')->update(
			array('unit_id' => 1)
		);
		DB::table('return_details')->update(
			array('unit_id' => 1)
		);
		foreach ($products as $item) {
			$data['pieces_num']       = 1;
			$data['unit_id']          = 1;
			$data['product_id']       = $item->id;
			$data['cost_price']       = $item->cost;
			$data['sale_price']       = $item->price;
			$data['price2']           = $item->price2;
			$data['price3']           = $item->price3;
			$data['default_sale']     = 1;
			$data['default_purchase'] = 1;
			$prodstore                = DB::table('products_store')->where('product_id', $item->id)->where('store_id', $store->id)->first();
			if (count($prodstore)) {

			} else {
				DB::table('products_store')->insert([
						'store_id'   => $store->id,
						'product_id' => $item->id,
						'qty'        => $item->quantity-$item->sale_count,
						'unit_id'    => 1,
						'cost'       => $data['cost_price']

					]);
			}

			DB::table('product_store_unit')->insert($data);
		}
		$all = \App\ProductsStore::get();
		foreach ($all as $v) {
			$el      = DB::table('oldproducts')->where('id', $v->product_id)->first();
			if(!isset($el->cost)){
				$v->delete();
				continue;
			}
			$v->cost = $el->cost;
			$v->save();
		}
		foreach (App\Orders::get() as $order) {
			$order->details()->update(['store_id' => $order->store_id ]);
		}


	}
}
