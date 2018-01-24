<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();
Route::get('migrate', function () {
	\Artisan::call('migrate');
	//\Artisan::call('db:seed');
	request()->session()->flash('alert-success', 'تمت العملية بنجاح migration');
	return redirect('/');
});
Route::get('updateOrderCost','OrdersController@updateOrderCost');
Route::get('handleClientOrder','OrdersController@handleClientOrder');
Route::get('/pdf', function () {
		$pdf = \PDF2::make(array(
				'footer-center' => '[page]/[topage]',
			));
		$list = \App\Products::get();
		$html = view('products.print', compact('list'));
		$html = $html->render();
		$pdf->addPage($html);
		//$filename = "y.pdf";
		//$pdf->saveAs($filename);
		$pdf->send();

	});
Route::get('restore', 'HomeController@restore');
Route::post('restore', 'HomeController@restore');
Route::group(['middleware' => 'auth'], function () {
		Route::get('users/destroy/{id}', 'UsersController@destroy');
		Route::resource('users', 'UsersController');
		Route::get('role/destroy/{id}', 'RoleController@destroy');
		Route::resource('role', 'RoleController');
		Route::get('unit/destroy/{id}', 'UnitController@destroy');
		Route::resource('unit', 'UnitController');
		Route::get('/', 'HomeController@index');
		Route::get('/home', 'HomeController@index');
		Route::get('products/search/{term?}', 'ProductsController@search');
		Route::get('products/checkCode', 'ProductsController@checkCode');
		Route::get('products/qtyDetailes/{id}', 'ProductsController@qtyDetailes');
		Route::get('products/movement/{id}', 'ProductsController@movement');
		Route::get('products/barcode/{id}', 'ProductsController@barcode');

		Route::get('products/salesDetailes/{id}', 'ProductsController@salesDetailes');
		Route::get('productlistCode', 'ProductsController@productlistCode');
		Route::get('printPdf/{model}', 'HomeController@printPdf');
		Route::resource('/products', 'ProductsController');
		Route::get('products/destroy/{id}', 'ProductsController@destroy');
		Route::get('autocomplete', array('as' => 'autocomplete', 'uses' => 'PurchaseInvoiceController@autocomplete'));

		Route::get('purchaseInvoice/search', 'PurchaseInvoiceController@search');
		Route::get('purchaseInvoice/allDetailes', 'PurchaseInvoiceController@allDetailes');
		Route::resource('/purchaseInvoice', 'PurchaseInvoiceController');
		Route::get('orders/search', 'OrdersController@search');
		Route::get('orders/allDetailes', 'OrdersController@allDetailes');
		//Route::get('orders/removeItem','OrdersController@removeItem');
		//Route::get('orders', ['as' => 'orders.index', 'uses' => 'OrdersController@index']);
		Route::resource('/orders', 'OrdersController');
		Route::get('purchaseInvoice/destroy/{id}', 'PurchaseInvoiceController@destroy');

		Route::get('clients/payments/{term?}', 'ClientsController@payments');
		Route::get('clients/search/{term?}', 'ClientsController@search');
		Route::resource('/clients', 'ClientsController');
		Route::get('clients/destroy/{id}', 'ClientsController@destroy');
		Route::get('clients/pay/{id}', 'ClientsController@pay');

		Route::post('supplierpay', ['as' => 'supplierpay', 'uses' => 'SuppliersController@addpay']);
		Route::post('addpay', ['as'      => 'addpay', 'uses'      => 'ClientsController@addpay']);

		Route::get('suppliers/payments/{term?}', 'SuppliersController@payments');
		Route::get('suppliers/search', 'SuppliersController@search');
		Route::resource('/suppliers', 'SuppliersController');
		Route::get('suppliers/destroy/{id}', 'SuppliersController@destroy');
		Route::get('suppliers/pay/{id}', 'SuppliersController@pay');
		Route::resource('/shoraka', 'ShorakaController');
		Route::get('shoraka/destroy/{id}', 'ShorakaController@destroy');
		Route::get('masrofat/search/{term?}', 'MasrofatController@search');
		Route::resource('/masrofat', 'MasrofatController');
		Route::get('masrofat/destroy/{id}', 'MasrofatController@destroy');
		Route::get('setting', array('as'       => 'setting.index', 'uses'       => 'SettingController@index'));
		Route::post('setting/edit', array('as' => 'setting.edit', 'uses' => 'SettingController@update'));
		//Route::post('dropdb',['as'=>'dropdb','uses'=>'HomeController@dropdb']);
		Route::resource('/category', 'CategoryController');
		Route::get('category/destroy/{id}', 'CategoryController@destroy');
		Route::get('treasury/search/{term?}', 'TreasuryMovementController@search');
		Route::resource('/treasury', 'TreasuryMovementController');
		Route::get('returns/search', 'ReturnsController@search');
		Route::resource('returns', 'ReturnsController');
		Route::get('ordersreturns/search', 'OrdersReturnsController@search');
		Route::resource('ordersreturns', 'OrdersReturnsController');
		//Route::get('backup','HomeController@backup');
		Route::get('closeYear', 'HomeController@closeYear');
		Route::resource('store', 'StoreController');
		Route::resource('transit', 'TransitController');
		Route::get('store/destroy/{id}', 'StoreController@destroy');
		Route::get('daily', 'HomeController@daily');
		Route::get('report', 'HomeController@report');
		Route::get('downloaddb', function () {
				$file = storage_path()."/database.sqlite";
				return Response::download($file, date('Y-m-d').' database.sqlite');
			});
		Route::get('clientsupplier', 'HomeController@clientsupplier');
		Route::get('bank/withdraw/{id}','BankController@withdraw');
		Route::post('withdraw',['as'=>'withdraw','uses'=>'BankController@withdrawsave']);
		Route::resource('bank', 'BankController');
		Route::post('cheq/changeStatus', 'CheqController@changeStatus');
		Route::any('cheq/index', 'CheqController@index');
		Route::resource('cheq', 'CheqController');


});