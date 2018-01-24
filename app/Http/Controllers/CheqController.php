<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cheq;
use DB;
class CheqController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

	public function index(Request $request) {
		$title = "Cheq Index";
    	$list = Cheq::search();

		if ($request->ajax()) {
			return \View::make('cheq._list', compact('list'));
		} else {
			return view('cheq.index', compact('title','list'));
		}
	}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Cheq | Create';
        $item = new Cheq;
        return view('cheq.create',compact('title','item'));
    }

    /**
     * Record a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
	    $inputs['auto']       = isset($inputs['auto'])?1:0;
        Cheq::create($inputs);
        return redirect('cheq');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $item = Cheq::findOrFail($id);
         $title = 'Cheq | '.$item->title; 
         return view('cheq.edit',compact('item','title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $Record = Cheq::find($id);
        $inputs = $request->all();
	    $inputs['auto']       = isset($inputs['auto'])?1:0;
        $Record->update($inputs);
        return redirect('cheq');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Cheq::find($id)->delete();
    }
	public function changeStatus(Request $request) {
		try {
			DB::beginTransaction();
	        $elmid  = $request->get('elmid');
			$status = $request->get('status');
			$cheq = Cheq::find($elmid);
			$cheq->update(['is_paid' => $status]);
			$inputs["bank_id"] = $cheq->bank_id;
			$inputs["note"] = "خصم قيمة الشيك رقم ".$cheq->cheq_num." للمورد ".$cheq->supplier->name;
			$inputs["op_date"] = date('Y-m-d');
			$inputs["type"] = "1";
			$inputs["total"] = $cheq->bank->balance;
			$inputs["value"] = $cheq->value;
			$inputs["due"] = $cheq->bank->balance-$cheq->value;
			\App\Transaction::create($inputs);
			$cheq->bank->balance-=$cheq->value;
			$cheq->bank->save();
			$supplier = \App\Suppliers::find($cheq->supplier_id);
			$payment["supplier_id"] = $cheq->supplier_id;
			$payment["esal_num"] =  "رقم الشيك".$cheq->cheq_num;
			$payment["created_at"] = date('Y-m-d');
			$payment["total"] = $supplier->due;
			$payment["paid"] = $cheq->value;
	        $payment["due"] = $supplier->due - $cheq->value;
            $payment["payment_type"] = 2;
			\App\SupplierPayments::create($payment);
			$supplier->paid += $cheq->value;
			$supplier->due -= $cheq->value;
			$supplier->save();
			DB::commit();
			return 1;
		} catch (\Exception $e) {
			DB::rollback();
			return 0;
			dd($e->getMessage());
		}

	}

    public function show($id)
    {

    }


}
