<?php

namespace App\Http\Controllers;

use App\Bank;
use App\ClientPayments;
use App\Clients;
use App\OrderDetails;
use App\Orders;
use App\OrdersReturns;
use App\Products;
use App\ProductsStore;
use App\ProductStoreUnit;
use App\Store;
use App\Transaction;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class OrdersController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		if ($request->get('sort')) {
			$list = Orders::sortable()->Paginate(\config('custom-setting.page_size'));
		} else {
			$list = Orders::sortable()->orderBy('id', 'DESC')->Paginate(\config('custom-setting.page_size'));

		}
		$title         = "Orders Index";
		$masrofat      = \App\Masrofat::where('type', '1')->get();
		$totalMasrofat = $masrofat->sum('value');
		//$detailes = OrderDetails::get();
		//$totalprofit = $detailes->sum('(qty * price) - (qty * cost)');
		$totalprofit = DB::table('order_detailes')->sum(DB::raw('(qty * price) - (qty * cost)'));
		$finalpofit  = $totalprofit;

		//dd($finalpofit);
		return view('orders.index', compact('title', 'list', 'finalpofit', 'totalprofit', 'totalMasrofat'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		$title = 'orders | Create';
		$item  = new Orders;
		return view('orders.create', compact('title', 'item'));
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
			$inputs                   = $request->all();
			$invoice['client_id']     = $inputs['client_id'];
            $invoice['payment_type']  = $inputs['payment_type'];
            $invoice['bank_id']  = $inputs['bank_id'];
			$invoice['is_paid']       = ($inputs['payment_type'] == 1)?1:0;
			$invoice['paid']          = $inputs['paid'];
			$invoice['due']           = $inputs['due'];
			$invoice['total']         = $inputs['total'];
			$invoice['discount']      = $inputs['discount'];
			$invoice['discount_type'] = isset($inputs['discount_type'])?2:1;
			$invoice['created_at']    = $inputs['created_at'];
			$invoice['id']            = $inputs['id'];
			$invoice_id               = Orders::create($invoice)->id;
			$first                    = Store::first()->id;
			foreach ($inputs['product_id'] as $key => $value) {
				$prod                  = explode('-', $value);
				$product               = Products::findOrFail($prod[0]);
				$details['order_id']   = $invoice_id;
				$details['product_id'] = $prod[0];
				$details['qty']        = $inputs['quantity'][$key];
				$details['price']      = $inputs['price'][$key];
				$details['cost']       = $inputs['cost'][$key];
				$details['total']      = $inputs['totalcost'][$key];
				$details['store_id']   = $inputs['store_id'][$key];
				$details['created_at'] = $inputs['created_at'];
				$details['unit_id']    = isset($inputs['store_unit'][$key])?$inputs['store_unit'][$key]:'';
				$avilableQty           = 0;
				$storeData             = Store::find($details['store_id']);
				$storeName             = $storeData->address;

				/*if($first==$details['store_id']){
				$avilableQty = $product->quantity-$product->sale_count;
				}else{*/
				$storeqty    = ProductsStore::where('product_id', $prod[0])->where('store_id', $details['store_id'])->first();
				$avilableQty = $storeqty->qty-$storeqty->sale_count;
				if (!$storeqty) {
					throw new \Exception($product->title.' غير متاح فى '.$storeName);
				} else {

					$remainQty = $storeqty->qty-$storeqty->sale_count;
					$orderQty  = $details['qty'];
					if ($details['unit_id'] && !empty($details['unit_id'])) {
						$prodstorUnit = $storeqty->unit_id;

						if ($prodstorUnit != $details['unit_id']) {
							$orderUnit = ProductStoreUnit::where('product_id', $prod[0])->where('unit_id', $details['unit_id'])->first();
							$storUnit  = ProductStoreUnit::where('product_id', $prod[0])->where('unit_id', $prodstorUnit)->first();

							//dd($prodstorUnit);
							if ($storUnit->pieces_num == $orderUnit->pieces_num) {
								$orderQty = $details['qty'];
							} elseif ($storUnit->pieces_num > $orderUnit->pieces_num) {
								$avilableQty = $storUnit->pieces_num*$remainQty;
								//$remain = $avilableQty - $details['qty'];
								$orderQty = $details['qty']/$storUnit->pieces_num;
								//$remain = $remain/$storUnit->pieces_num;
							} else {
								$avilableQty = $remainQty/$orderUnit->pieces_num;
								//$remain = $avilableQty - $details['qty'];
								$orderQty = $details['qty']*$orderUnit->pieces_num;
								//dd($orderQty);
								//$remain = $remain*$storUnit->pieces_num;
							}
						}
					} else {
						//$avilableQty = $details['qty'];
						$orderQty = $details['qty'];
					}
					//$storeqty->save();
				}
				//}
				if ($avilableQty < $details['qty']) {throw new \Exception($product->title.' الكمية غير متاحة فى '.$storeName.' الكمية المتاحة هى '.$avilableQty);
				}

				OrderDetails::create($details);
				$storeqty->sale_count += $orderQty;
				$storeqty->save();

			}
            if($invoice['payment_type']==3) {
                $bank = Bank::find($invoice["bank_id"]);
                $trans["bank_id"] = $invoice["bank_id"];
                $trans["note"] = "فاتورة مبيعات رقم  " . $invoice_id;
                $trans["op_date"] = date('Y-m-d');
                $trans["type"] = "2";
                $trans["total"] = $bank->balance;
                $trans["value"] = $invoice['paid'];
                $trans["due"] = $bank->balance + $invoice['paid'];
                Transaction::create($trans);
                $bank->balance += $invoice['paid'];
                $bank->save();
			}
            $client = Clients::find($inputs['client_id']);
            if ($inputs['due'] < 0) {
                $Paymentinputs['client_id']  = $client->id;
                $Paymentinputs['total']      = $client->due;
                $Paymentinputs['paid']       = abs($inputs['due']);
                $Paymentinputs['due']        = $client->due-abs($inputs['due']);
                $Paymentinputs['created_at'] = date("Y-m-d H:i:s");
                ClientPayments::create($Paymentinputs);
            }

            $client->total += $inputs['total']-$inputs['discount'];
            $client->paid += $inputs['paid'];
            $client->due += $inputs['due'];
            $client->save();

			$request->session()->flash('alert-success', trans('app.Orders Invoice was successful added!'));
			DB::commit();

			return redirect(url('orders', ['id' => $invoice_id]));
		} catch (\Exception $e) {
			DB::rollback();
			//var_dump($e->getMessage());die;
			$request->session()->flash('alert-danger', trans('app.Some Error was ocuured during adding! ').$e->getMessage());

			return redirect('orders/create');
		}
		return redirect('orders');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$invoice = Orders::with('details')->findOrFail($id);
		$title   = 'orders | '.$invoice->id;
		$view    = "show";
		if (\Config::get('custom-setting.invoiceTemplate') == 2) {
			$view = "invoice";
		}
		return view("orders.$view", compact('invoice', 'title'));
	}
	public function allDetailes() {
		$list = Orders::with('details')->orderBy('id', 'DESC')->Paginate(\config('custom-setting.page_size'));
		return view("orders.allDetailes", compact('list'));
	}
	public function autocomplete(Request $request) {
		$data = Products::selectRaw('CONCAT(id, "-", title) as name, id')->where("title", "LIKE", "%{$request->input('query')}%")->get();
		return response()->json($data);
	}
	public function search(Request $request) {
		$list          = Orders::search($request->get('from'), $request->get('to'), $request->get('client'));
		$masrofat      = \App\Masrofat::where('type', 1)->search($request->get('from'), $request->get('to'), '');
		$totalMasrofat = $masrofat->sum('value');
		$from          = $request->get('from');
		$to            = $request->get('to');
		if (!empty($from) && !empty($to)) {
			$totalprofit = DB::table('order_detailes')->whereBetween('created_at', array($from, $to))->sum(DB::raw('(qty * price) - (qty * cost)'));
		} elseif (!empty($from)) {
			$totalprofit = DB::table('order_detailes')->where('created_at', '>=', $from)->sum(DB::raw('(qty * price) - (qty * cost)'));
		} elseif (!empty($to)) {
			$totalprofit = DB::table('order_detailes')->where('created_at', '<=', $to)->sum(DB::raw('(qty * price) - (qty * cost)'));
		} else {
			$totalprofit = DB::table('order_detailes')->sum(DB::raw('(qty * price) - (qty * cost)'));
		}
		//$totalprofit = $detailes->sum(DB::raw('(qty * price) - (qty * cost)'));
		//dd($totalprofit);
		$finalpofit = $totalprofit;
		$indexView  = 'index';
		$listView   = '_list';
		if ($request->get('view') && $request->get('view') == 2) {
			$indexView = 'allDetailes';
			$listView  = '_listdetails';
		}
		if ($request->ajax()) {
			return \View::make("orders.$listView", compact('list', 'finalpofit', 'totalprofit', 'totalMasrofat'));
		} else {
			$title = "Orders | Create";
			return view("orders.$indexView", compact('title', 'list', 'finalpofit', 'totalprofit', 'totalMasrofat'));
		}
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		$item  = Orders::findOrFail($id);
		$title = 'Orders | '.$item->title;
		return view('orders.edit', compact('item', 'title'));
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
			$inputs = $request->all();
			//echo '<pre>';
			//dd($inputs);
			$invoice['client_id']     = $inputs['client_id'];
			$invoice['payment_type']  = $inputs['payment_type'];
            $invoice['bank_id']  = $inputs['bank_id'];
			$invoice['is_paid']       = ($inputs['payment_type'] == 1)?1:0;
			$invoice['paid']          = $inputs['paid'];
			$invoice['due']           = $inputs['due'];
			$invoice['total']         = $inputs['total'];
			$invoice['discount']      = $inputs['discount'];
			$invoice['discount_type'] = isset($inputs['discount_type'])?2:1;
			$invoice['created_at']    = $inputs['created_at'];
			$invoice['id']            = $inputs['id'];
			$order                    = Orders::find($id);
			$invoice_id               = $id;
			//$order->details()->update(['store_id' => $order->store_id ]);
			$olddetails               = $order->details;
			foreach ($olddetails as $v) {
				$returnQty = $v->qty;
				$storeqty  = ProductsStore::where('product_id', $v->product_id)->where('store_id', $v->store_id)->first();
				//dd($v->store_id);
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
				$storeqty->sale_count -= $returnQty;
				$storeqty->sale_count = ($storeqty->sale_count<0)?0:$storeqty->sale_count;
				$storeqty->save();
				$v->delete();
			}

			$client = Clients::find($inputs['client_id']);
			$client->total -= $order->total;
			$client->paid -= $order->paid;
			$client->due -= $order->due;
			$client->save();

            if($order->getOriginal('payment_type')==3) {
                $bank = Bank::find($order->bank_id);
                $trans["bank_id"] = $order->bank_id;
                $trans["note"] = "تعديل فاتورة مبيعات رقم  " . $order->id;
                $trans["op_date"] = date('Y-m-d');
                $trans["type"] = "1";
                $trans["total"] = $bank->balance;
                $trans["value"] = $order->paid;
                $trans["due"] = $bank->balance - $order->paid;
                Transaction::create($trans);
                $bank->balance -= $order->paid;
                $bank->save();
            }
			$order->update($inputs);
			foreach ($inputs['product_id'] as $key => $value) {

				$prod                  = explode('-', $value);
				$product               = Products::findOrFail($prod[0]);
				$details['order_id']   = $invoice_id;
				$details['product_id'] = $prod[0];
				$details['qty']        = $inputs['quantity'][$key];
				$details['price']      = $inputs['price'][$key];
				$details['cost']       = $inputs['cost'][$key];;
				$details['total']      = $inputs['totalcost'][$key];
				$details['store_id']   = $inputs['store_id'][$key];
				$details['created_at'] = $inputs['created_at'];
				$details['unit_id']    = isset($inputs['store_unit'][$key])?$inputs['store_unit'][$key]:'';
				$avilableQty           = 0;
				$storeData             = Store::find($details['store_id']);
				$storeName             = $storeData->address;

				/*if($first==$details['store_id']){
				$avilableQty = $product->quantity-$product->sale_count;
				}else{*/
				$storeqty    = ProductsStore::where('product_id', $prod[0])->where('store_id', $details['store_id'])->first();
				$avilableQty = $storeqty->qty-$storeqty->sale_count;
				if (!$storeqty) {
					throw new \Exception($product->title.' غير متاح فى '.$storeName);
				} else {

					$remainQty = $storeqty->qty-$storeqty->sale_count;
					$orderQty  = $details['qty'];
					if ($details['unit_id'] && !empty($details['unit_id'])) {
						$prodstorUnit = $storeqty->unit_id;

						if ($prodstorUnit != $details['unit_id']) {
							$orderUnit = ProductStoreUnit::where('product_id', $prod[0])->where('unit_id', $details['unit_id'])->first();
							$storUnit  = ProductStoreUnit::where('product_id', $prod[0])->where('unit_id', $prodstorUnit)->first();

							//dd($prodstorUnit);
							if ($storUnit->pieces_num == $orderUnit->pieces_num) {
								$orderQty = $details['qty'];
							} elseif ($storUnit->pieces_num > $orderUnit->pieces_num) {
								$avilableQty = $storUnit->pieces_num*$remainQty;
								//$remain = $avilableQty - $details['qty'];
								$orderQty = $details['qty']/$storUnit->pieces_num;
								//$remain = $remain/$storUnit->pieces_num;
							} else {
								$avilableQty = $remainQty/$orderUnit->pieces_num;
								//$remain = $avilableQty - $details['qty'];
								$orderQty = $details['qty']*$orderUnit->pieces_num;
								//dd($orderQty);
								//$remain = $remain*$storUnit->pieces_num;
							}
						} else {
							$orderQty = $details['qty'];
						}
					} else {
						//$avilableQty = $details['qty'];
						$orderQty = $details['qty'];
					}
				}

				//}
				if ($avilableQty < $details['qty']) {throw new \Exception($product->title.' الكمية غير متاحة فى '.$storeName.' الكمية المتاحة هى '.$avilableQty);
				}

				OrderDetails::create($details);
				$storeqty->sale_count += $orderQty;
				$storeqty->save();

			}

            if($invoice['payment_type']==3) {
                $bank = Bank::find($invoice["bank_id"]);
                $trans["bank_id"] = $invoice["bank_id"];
                $trans["note"] = "فاتورة مبيعات رقم  " . $invoice_id;
                $trans["op_date"] = date('Y-m-d');
                $trans["type"] = "2";
                $trans["total"] = $bank->balance;
                $trans["value"] = $invoice['paid'];
                $trans["due"] = $bank->balance + $invoice['paid'];
                Transaction::create($trans);
                $bank->balance += $invoice['paid'];
                $bank->save();
            }
			//if($inputs['payment_type']==2){
			$client = Clients::find($inputs['client_id']);
			$client->total += $inputs['total']-$inputs['discount'];
			$client->paid += $inputs['paid'];
			$client->due += $inputs['due'];
			$client->save();
			//}
			$request->session()->flash('alert-success', trans('app.Orders Invoice was successful added!'));
			DB::commit();

			return redirect(url('orders', ['id' => $invoice_id]));
		} catch (\Exception $e) {
			DB::rollback();
			//var_dump($e->getMessage());
			//die;
			$request->session()->flash('alert-danger', trans('app.Some Error was ocuured during adding! ').$e->getMessage());
			return redirect()->back();
		}
		return redirect('orders');
	}
	public function updateOrderCost(){
		$list = OrderDetails::where('cost',0)->get();

		foreach ($list as $item){
			$productStore = ProductsStore::where('product_id',$item->product_id)
				->where('unit_id',$item->unit_id)
				->where('store_id',$item->store_id)->first();
			$item->cost = ((float)$productStore->cost) ? $productStore->cost : $item->price;
			$item->save();
		}
		dd('done');

	}
	public function handleClientOrder(){

		$clients = Clients::where('id',26)->get();
		foreach ($clients as $cl){
			$totalPayments = 0;
			$clienId = $cl->id;
			$payments = ClientPayments::where('client_id',$clienId)->orderBy('created_at')->get();
			foreach ($payments as $p){
				$date = Carbon::create($p->created_at->year, $p->created_at->month, $p->created_at->day, 23, 23, 59);
				$ordersTotal = Orders::where('client_id', $clienId)
					->where('created_at', '<=', $date)
					->orderBy('created_at')
					->sum('due');
				$totalreturns = OrdersReturns::where('client_id',$clienId)
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
	/*public function removeItem(){
try{
DB::beginTransaction();
$order = $request->get('order');
$prod = $request->get('prod');
$deatils = OrderDetails::where([['product_id',$prod],['order_id',$order]])->get();
$product = Products::findOrFail($prod);
$product->sale_count -= $deatils->qty;
$product->save();
$deatils->delete();
DB::commit();
}catch(\Exception $e){
DB::rollback();
}
}*/
}
