<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Returns;
use App\Products;
use App\ReturnDetails;
use DB;
use App\Suppliers;
use App\ProductsStore;
use App\ProductStoreUnit;
class ReturnsController extends BaseController
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->get('sort')){
            $list = Returns::sortable()->Paginate(\config('custom-setting.page_size'));
        }else{
            $list = Returns::sortable()->orderBy('id','DESC')->Paginate(\config('custom-setting.page_size'));
        }
        
        $title = "Returns Index";
        return view('returns.index',compact('title','list'));
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Returns | Create';
        return view('returns.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	try{
    		DB::beginTransaction();
	        $inputs = $request->all();
	        $invoice['supplier_id'] = $inputs['supplier_id'];
	        $invoice['total'] = $inputs['total'];
            $invoice['created_at'] =$inputs['created_at'];
            $invoice['is_subtract'] = $request->has('is_subtract')?1:0;
            $invoice['store_id'] =$inputs['store_id'];
	        $invoice_id = Returns::create($invoice)->id;
	        foreach ($inputs['product_id'] as $key => $value) {
	        	$prod = explode('-', $value);
                $productid = $prod[0];
	        	$details['return_id'] = $invoice_id;
	        	$details['product_id'] = $productid;
	        	$details['qty'] = $inputs['quantity'][$key];
	        	$details['cost'] = $inputs['cost'][$key];
	        	$details['total'] = $inputs['totalcost'][$key];
                $details['unit_id'] = isset($inputs['store_unit'][$key])?$inputs['store_unit'][$key]:'';
				ReturnDetails::create($details);
                $storeRaw =  \App\ProductsStore::where('product_id',$productid)->where('store_id',$invoice['store_id'])->first();

                $remainQty = $storeRaw->qty - $storeRaw->sale_count;
                $orderQty=$details['qty'];
                if($details['unit_id'] && !empty($details['unit_id'])){
                    $prodstorUnit = $storeRaw->unit_id;
                    $storUnit = ProductStoreUnit::where('product_id',$prod[0])->where('unit_id',$prodstorUnit)->first();

                    if($prodstorUnit != $details['unit_id']){
                        $orderUnit = ProductStoreUnit::where('product_id',$prod[0])->where('unit_id',$details['unit_id'])->first();
                        if($storUnit->pieces_num == $orderUnit->pieces_num){
                            $orderQty=$details['qty'];
                        }elseif($storUnit->pieces_num > $orderUnit->pieces_num){
                            $orderQty=$details['qty']/$storUnit->pieces_num;
                        }else{
                            $orderQty=$details['qty'] * $orderUnit->pieces_num;
                        }   
                    }
                }
                if(count($storeRaw)){
                    $store['qty'] = $storeRaw->qty - $orderQty;
                    $storeRaw->update($store);
                }

	        }
            $suppliers = Suppliers::find($inputs['supplier_id']);
            if($invoice['is_subtract']){
                $total = $suppliers->total - $invoice['total'];
                $suppliers->total = $total;
                $suppliers->due = $suppliers->due - $invoice['total'];
                $suppliers->save();
            }else{
                $tres['title'] = 'ايداع قيمة مرتجعات مشتريات من المورد '.$suppliers->name;
                $tres['client_id'] = '';
                $tres['supplier_id'] = $suppliers->id;
                $tres['partner_id'] = '';
                $tres['value'] = $invoice['total'];
                $tres['type'] = 2;
                $tres['user_type'] = 2;
                \App\TreasuryMovement::create($tres);
            }
            $request->session()->flash('alert-success', 'تمت العملية بنجاح');
		    DB::commit();
		}catch(\Exception $e){
		    DB::rollback();
		     $request->session()->flash('alert-danger', trans('حدثت مشكلة').$e->getMessage());
		}
        return redirect('returns');
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
         $item = Returns::findOrFail($id);
         $title = 'Returns | '.$item->title; 
         return view('returns.edit',compact('item','title'));
    }

     public function search(Request $request){
        $supplier_id = $request->get('supplier_id');
        if(!empty($supplier_id)){
            $list =  Returns::where('supplier_id',$supplier_id)->search($request->get('from'),$request->get('to'));     
        }else{
            $list =  Returns::search($request->get('from'),$request->get('to'));     
        }
        $indexView = 'index';
        $listView = '_listdetails';
        if($request->ajax()){
            return \View::make("returns.$listView",compact('list'));
        }else{ 
            $title = 'Returns | Create';
            return view("returns.$indexView",compact('title','list'));
        }
    }
}
