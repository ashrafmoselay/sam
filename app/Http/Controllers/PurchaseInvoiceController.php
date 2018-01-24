<?php

namespace App\Http\Controllers;

use App\InvoiceDetailes;
use App\Products;
use App\ProductsStore;
use App\ProductStoreUnit;
use App\PurchaseInvoice;
use App\Returns;
use App\Store;
use App\SupplierPayments;
use App\Suppliers;
use DB;
use Illuminate\Http\Request;

class PurchaseInvoiceController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$list  = PurchaseInvoice::search();
		$title = "PurchaseInvoice Index";
		return view('purchaseInvoice.index', compact('title', 'list'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$title = 'purchaseInvoice | Create';
		$item  = new PurchaseInvoice;
		return view('purchaseInvoice.create', compact('title', 'item'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		try {
			DB::beginTransaction();
			$inputs = $request->all();
			//dd($inputs);
			$invoice['supplier_id'] = $inputs['supplier_id'];
			$invoice['total']       = $inputs['total'];
			$invoice['paid']        = $inputs['paid'];
			$invoice['due']         = $inputs['due'];
			$invoice['discount']    = $inputs['discount'];
			$invoice['offer']       = $inputs['offer'];
			$invoice['note']        = $inputs['note'];
			$invoice['created_at']  = $inputs['created_at'];
			//$invoice['store_id'] =$inputs['store_id'];
			$invoice['discount_type'] = isset($inputs['discount_type'])?2:1;
			$invoice['id']            = $inputs['id'];
			$invoice_id               = PurchaseInvoice::create($invoice)->id;
			foreach ($inputs['product_id'] as $key => $value) {
				$newprod = isset($inputs['isnew'][$key])?true:false;
				if ($newprod) {
					$productid = $this->addNewProduct($inputs, $key);
				} else {
					$prod      = explode('-', $value);
					$productid = $prod[0];
				}
				$details['product_id'] = $productid;
				$details['invoice_id'] = $invoice_id;
				$details['qty']        = $inputs['quantity'][$key];
				$details['cost']       = $inputs['cost'][$key];
				$details['total']      = $inputs['totalcost'][$key];
				$details['unit_id']    = isset($inputs['store_unit'][$key])?$inputs['store_unit'][$key]:'';
				$details['store_id']   = $inputs['store_id'][$key];
				InvoiceDetailes::create($details);
				if (!$newprod) {
					$product = Products::findOrFail($productid);

					//$product->cost = $avgCost;
					$store['store_id']   = $details['store_id'];
					$store['qty']        = $details['qty'];
					$store['product_id'] = $productid;
					$storeRaw            = \App\ProductsStore::where('product_id', $productid)->where('store_id', $store['store_id'])->first();
					//$product->last_avg_cost = 0;
					$remainQty = $storeRaw->qty-$storeRaw->sale_count;
					$orderQty  = $details['qty'];
					if ($details['unit_id'] && !empty($details['unit_id'])) {
						$prodstorUnit = $storeRaw->unit_id;
						$storUnit     = ProductStoreUnit::where('product_id', $prod[0])->where('unit_id', $prodstorUnit)->first();

						if ($prodstorUnit != $details['unit_id']) {
							$orderUnit = ProductStoreUnit::where('product_id', $prod[0])->where('unit_id', $details['unit_id'])->first();
							if ($storUnit->pieces_num == $orderUnit->pieces_num) {
								$orderQty = $details['qty'];
							} elseif ($storUnit->pieces_num > $orderUnit->pieces_num) {
								//$avilableQty = $storUnit->pieces_num * $remainQty;
								//$remain = $avilableQty - $details['qty'];
								$orderQty = $details['qty']/$storUnit->pieces_num;
								//$remain = $remain/$storUnit->pieces_num;
							} else {
								//$avilableQty = $remainQty / $orderUnit->pieces_num ;
								//$remain = $avilableQty - $details['qty'];
								$orderQty = $details['qty']*$orderUnit->pieces_num;
								//dd($orderQty);
								//$remain = $remain*$storUnit->pieces_num;
							}
						}
					}
					if (count($storeRaw)) {
						$store['qty']  = $orderQty+$storeRaw->qty;
						$oldCost       = $storUnit->cost_price;
						$oldqty        = $storeRaw->qty-$storeRaw->sale_count;
						$newqty        = $orderQty;
						$newCost       = $details['cost'];
						$avgCost       = (($oldCost*$oldqty)+($newqty*$newCost))/($newqty+$oldqty);
						$store['cost'] = ($avgCost<=0)?$newCost:$avgCost;
						$storeRaw->update($store);
						$storUnit->cost_price = $avgCost;
						$storUnit->save();
						$product->last_avg_cost = $oldCost;
						$product->save();

					} else {
						$store['unit_id'] = $details['unit_id'];
						\App\ProductsStore::create($store);
					}
					//$product->save();
				}

			}

			$suppliers = Suppliers::find($inputs['supplier_id']);

			if ($inputs['due'] < 0) {
				$Paymentinputs['supplier_id'] = $suppliers->id;
				$Paymentinputs['total']       = $suppliers->due;
				$Paymentinputs['paid']        = abs($inputs['due']);
				$Paymentinputs['due']         = $suppliers->due-abs($inputs['due']);
				$Paymentinputs['created_at']  = date("Y-m-d H:i:s");
				$Paymentinputs['esal_num']    = " هذا المبلغ مدفوع مع الفاتورة رقم ".$invoice_id;
				SupplierPayments::create($Paymentinputs);
			}
			$suppliers->total += $inputs['total']-$inputs['discount']-$inputs['offer'];
			$suppliers->paid += $inputs['paid'];
			$suppliers->due += $inputs['due'];
			$suppliers->save();
			$request->session()->flash('alert-success', trans('app.Orders Invoice was successful added!'));
			DB::commit();
			return redirect(url('purchaseInvoice', ['id' => $invoice_id]));
		} catch (\Exception $e) {
			DB::rollback();
			dd($e->getMessage());
			$request->session()->flash('alert-danger', trans('app.Some Error was ocuured during adding! ').$e->getMessage());
		}
		return redirect('purchaseInvoice');
	}

	public function addNewProduct($inputs, $k) {
		//$p['last_avg_cost'] = $inputs['cost'][$k];
		//$p['cost'] = $inputs['cost'][$k];
		$p['code']        = str_random(6).rand(1, 9);
		$p['title']       = $inputs['product_id'][$k];
		$p['description'] = '';
		//$p['price'] = $p['cost'] + 50;
		//$p['price2'] = '';
		//$p['price3'] = '';
		$p['model'] = '';

		$id                  = Products::create($p)->id;
		$store['store_id']   = $inputs['store_id'][$k];
		$store['qty']        = $inputs['quantity'][$k];
		$store['cost']       = $inputs['cost'][$k];
		$store['unit_id']    = isset($inputs['store_unit'][$k])?$inputs['store_unit'][$k]:'';
		$store['product_id'] = $id;
		\App\ProductsStore::create($store);
		if ($store['unit_id']) {
			$data['unit_id']          = $store['unit_id'];
			$data['pieces_num']       = 1;
			$data['product_id']       = $id;
			$data['cost_price']       = $inputs['cost'][$k];
			$data['sale_price']       = $inputs['cost'][$k]+50;
			$data['price2']           = $data['sale_price'];
			$data['price3']           = $data['sale_price'];
			$data['default_sale']     = 0;
			$data['default_purchase'] = 0;
			ProductStoreUnit::create($data);
		}
		return $id;
	}
	public function getProdAvg($pid, $newQty, $newCost) {
		$query = DB::table('products')
			->join('products_store', 'product_id', 'products.id')
			->where('product_id', $pid)
			->select(DB::raw('sum((qty-sale_count)*products_store.cost)  as TotalCost'))	->first();
		$totalRemainInStockCost = isset($query->TotalCost)?$query->TotalCost:0;
		$remainQty              = isset($query->TotalQty)?$query->TotalQty:0;
		$totalQty               = $newQty+$remainQty;
		$avgCost                = $totalRemainInStockCost;
		if ($totalQty) {
			$avgCost = ($totalRemainInStockCost+($newQty*$newCost))/($newQty+$remainQty);
		}
		return $avgCost;
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$invoice = PurchaseInvoice::with('details')->findOrFail($id);
		$title   = 'purchaseInvoice | '.$invoice->id;
		return view('purchaseInvoice.show', compact('invoice', 'title'));
	}
	public function autocomplete(Request $request) {
		$select = 'products.id || "-" || products.title';
		if (\Config::get('custom-setting.use_barcode') == 1) {
			$select = 'products.id || "-" || products.title || " " || code ';
		}
		$term     = $request->input('query');
		$q        = explode("-", $term);
		$term     = $q[0];
		$store_id = $request->store_id;
		$unit     = $request->unit;

		$data = Products::join('product_store_unit', function ($join) {
				$join->on('products.id', '=', 'product_store_unit.product_id');
			})->join('unit', function ($join) {
				$join->on('unit.id', '=', 'product_store_unit.unit_id');
			})->join('products_store', function ($join) use ($store_id) {
				$join->on('products_store.product_id', 'products.id');
				$join->on('products_store.store_id', $store_id);
			})
			->selectRaw($select.' as name,GROUP_CONCAT(product_store_unit.unit_id ) as unitid, GROUP_CONCAT(product_store_unit.cost_price ) as cost_price, GROUP_CONCAT(product_store_unit.sale_price ) as price, GROUP_CONCAT(product_store_unit.price2 ) as price2, GROUP_CONCAT(product_store_unit.price3 ) as price3, GROUP_CONCAT(unit.title) as title,(qty-sale_count) as quantity,products_store.unit_id as storeID')
			->where("products.title", "LIKE", "%$term%")
			->where('store_id', $store_id)
			->orwhere("products.id", "=", $term)	->groupBy('product_store_unit.product_id')	->groupBy('product_store_unit.product_id')	->take(20)	->get();
		return response()->json($data);
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$item  = PurchaseInvoice::findOrFail($id);
		$title = 'purchaseInvoice | '.$item->title;
		return view('purchaseInvoice.edit', compact('item', 'title'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		try {
			DB::beginTransaction();
			$inputs                   = $request->all();
			$invoice['supplier_id']   = $inputs['supplier_id'];
			$invoice['total']         = $inputs['total'];
			$invoice['paid']          = $inputs['paid'];
			$invoice['due']           = $inputs['due'];
			$invoice['discount']      = $inputs['discount'];
			$invoice['offer']         = $inputs['offer'];
			$invoice['note']          = $inputs['note'];
			$invoice['created_at']    = $inputs['created_at'];
			$invoice['id']            = $inputs['id'];
			$invoice['store_id']      = $inputs['store_id'];
			$invoice['discount_type'] = isset($inputs['discount_type'])?2:1;
			$order                    = PurchaseInvoice::find($id);
			$invoice_id               = $id;
			$olddetails               = $order->details;
			foreach ($olddetails as $v) {
				$returnQty    = $v->qty;
				$storeqty     = ProductsStore::where('product_id', $v->product_id)->where('store_id', $v->store_id)->first();
				$prodstorUnit = $storeqty->unit_id;
				if ($storeqty->unit_id == $v->unit_id) {
					$returnQty = $v->qty;
				} else {
					$orderUnit = ProductStoreUnit::where('product_id', $v->product_id)->where('unit_id', $v->unit_id)->first();
					$storeUnit = ProductStoreUnit::where('product_id', $v->product_id)->where('unit_id', $prodstorUnit)->first();
					if ($storeUnit->pieces_num > $orderUnit->pieces_num) {
						$returnQty = $v->qty/$storeUnit->pieces_num;
					} else {
						$returnQty = $v->qty*$orderUnit->pieces_num;
					}
				}
				$storeqty->qty -= $returnQty;
				$storeqty->qty = ($storeqty->qty<0)?0:$storeqty->qty;
				$storeqty->save();
				$v->delete();
			}
			$suppliers = Suppliers::find($inputs['supplier_id']);
			$suppliers->total -= ($order->total+$order->discount+$order->offer);
			$suppliers->paid -= $order->paid;
			$suppliers->due -= $order->due;
			$suppliers->save();
			$order->update($invoice);

			foreach ($inputs['product_id'] as $key => $value) {
				$newprod = isset($inputs['isnew'][$key])?true:false;

				if ($newprod) {
					$productid = $this->addNewProduct($inputs, $key);
				} else {
					$prod      = explode('-', $value);
					$productid = $prod[0];
				}
				$details['product_id'] = $productid;
				$details['invoice_id'] = $invoice_id;
				$details['qty']        = $inputs['quantity'][$key];
				$details['cost']       = $inputs['cost'][$key];
				$details['total']      = $inputs['totalcost'][$key];
				$details['unit_id']    = isset($inputs['store_unit'][$key])?$inputs['store_unit'][$key]:'';
				$details['store_id']   = $inputs['store_id'][$key];
				InvoiceDetailes::create($details);
				if (!$newprod) {
					$product = Products::findOrFail($productid);

					//$product->cost = $avgCost;
					$store['store_id']      = $details['store_id'];
					$store['qty']           = $details['qty'];
					$store['product_id']    = $productid;
					$storeRaw               = \App\ProductsStore::where('product_id', $productid)->where('store_id', $store['store_id'])->first();
					$product->last_avg_cost = 0;
					$remainQty              = $storeRaw->qty-$storeRaw->sale_count;
					$orderQty               = $details['qty'];
					if ($details['unit_id'] && !empty($details['unit_id'])) {
						$prodstorUnit = $storeRaw->unit_id;
						$storUnit     = ProductStoreUnit::where('product_id', $prod[0])->where('unit_id', $prodstorUnit)->first();

						if ($prodstorUnit != $details['unit_id']) {
							$orderUnit = ProductStoreUnit::where('product_id', $prod[0])->where('unit_id', $details['unit_id'])->first();
							if ($storUnit->pieces_num == $orderUnit->pieces_num) {
								$orderQty = $details['qty'];
							} elseif ($storUnit->pieces_num > $orderUnit->pieces_num) {
								//$avilableQty = $storUnit->pieces_num * $remainQty;
								//$remain = $avilableQty - $details['qty'];
								$orderQty = $details['qty']/$storUnit->pieces_num;
								//$remain = $remain/$storUnit->pieces_num;
							} else {
								//$avilableQty = $remainQty / $orderUnit->pieces_num ;
								//$remain = $avilableQty - $details['qty'];
								$orderQty = $details['qty']*$orderUnit->pieces_num;
								//dd($orderQty);
								//$remain = $remain*$storUnit->pieces_num;
							}
						}
					}
					if (count($storeRaw)) {
						$store['qty']  = $orderQty+$storeRaw->qty;
						$oldCost       = $storUnit->cost_price;
						$oldqty        = $storeRaw->qty-$storeRaw->sale_count;
						$newqty        = $orderQty;
						$newCost       = $details['cost'];
						//dd($newqty+$oldqty);
						$avgCost       = (($oldCost*$oldqty)+($newqty*$newCost))/($newqty+$oldqty);
						$store['cost'] = $avgCost;
						$storeRaw->update($store);
						$storUnit->cost_price = $avgCost;
						$storUnit->save();
					} else {
						$store['unit_id'] = $details['unit_id'];
						\App\ProductsStore::create($store);
					}
					$product->save();
				}

			}

			$suppliers = Suppliers::find($inputs['supplier_id']);
			$suppliers->total += $inputs['total']-$inputs['discount']-$inputs['offer'];
			$suppliers->paid += $inputs['paid'];
			$suppliers->due += $inputs['due'];
			$suppliers->save();
			$request->session()->flash('alert-success', 'تم تعديل الفاتورة بنجاح');
			DB::commit();
			return redirect(url('purchaseInvoice', ['id' => $invoice_id]));

		} catch (\Exception $e) {
			DB::rollback();
			//var_dump($e->getMessage());
			//die;
			$request->session()->flash('alert-danger', trans('app.Some Error was ocuured during adding! ').$e->getMessage());
		}
		return redirect('purchaseInvoice');
	}
	public function allDetailes() {
		$list = PurchaseInvoice::with('details')->Paginate(\config('custom-setting.page_size'));
		return view("purchaseInvoice.allDetailes", compact('list'));
	}
	public function search(Request $request) {
		$list        = PurchaseInvoice::search();
		$supplier_id = $request->get('supplier_id');
		$indexView   = 'index';
		$listView    = '_list';
		if ($request->get('view') && $request->get('view') == 2) {
			$indexView = 'allDetailes';
			$listView  = '_listdetails';
		}
		if ($request->ajax()) {
			return \View::make("purchaseInvoice.$listView", compact('list'));
		} else {
			$title = 'purchaseInvoice | Create';
			return view("purchaseInvoice.$indexView", compact('title', 'list'));
		}
	}


	public function handleSupplierOrder(){

		$suppliers = Suppliers::where('id',3)->get();
		foreach ($suppliers as $cl){
			$totalPayments = 0;
			$clienId = $cl->id;
			$payments = SupplierPayments::where('supplier_id',$clienId)->orderBy('created_at')->get();
			foreach ($payments as $p){
				$date = Carbon::create($p->created_at->year, $p->created_at->month, $p->created_at->day, 23, 23, 59);
				$ordersTotal = PurchaseInvoice::where('supplier_id', $clienId)
					->where('created_at', '<=', $date)
					->orderBy('created_at')
					->sum('due');
				$totalreturns = Returns::where('supplier_id',$clienId)
					->where('created_at','<=',$date)
					->orderBy('created_at')
					->sum('total');
				$totalPayments += $p->paid;
				$p->total = ($ordersTotal - ($totalreturns + $totalPayments) + $p->paid);
				$p->due = ($ordersTotal - ($totalreturns + $totalPayments));
				$p->save();
			}
//			$client_order = Orders::where('client_id', $clienId)->sum('total');
//			$paid_order = Orders::where('client_id', $clienId)->sum('paid');
//			$client_returns = OrdersReturns::where('client_id', $clienId)->sum('total');
//			$cl->total = $client_order - $client_returns;
//			$cl->paid = $totalPayments + $paid_order;
//			$cl->due = $client_order - ($cl->paid+$client_returns);
//			$cl->save();

		}

		dd('here');
	}

}
