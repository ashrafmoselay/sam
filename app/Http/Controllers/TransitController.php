<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transit;
use App\ProductsStore;
use App\Products;
use App\Store;
use DB;
use App\ProductStoreUnit;
class TransitController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $title = "Transit";
        return view('products.transit',compact('title','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return
     * \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            DB::beginTransaction();
            $inputs = $request->all();
            foreach ($inputs['product_id'] as $key => $value) {
                $from = $inputs['from_store_id'][$key];
                $to = $inputs['to_store_id'][$key];
                $prod = explode('-', $value);
                $productid = $prod[0];
 
                $qty = $inputs['qty'][$key];
                $storeFrom = ProductsStore::where('product_id',$productid)->where('store_id',$from)->first();

                $unit_id = $inputs['store_unit'][$key];
                $orderQty=$qty;
                if($unit_id && !empty($unit_id)){
                    $prodstorUnit = $storeFrom->unit_id;
                    $storUnit = ProductStoreUnit::where('product_id',$productid)->where('unit_id',$prodstorUnit)->first();

                    if($prodstorUnit != $unit_id){
                        $orderUnit = ProductStoreUnit::where('product_id',$productid)->where('unit_id',$unit_id)->first();
                        if($storUnit->pieces_num == $orderUnit->pieces_num){
                            $orderQty=$qty;
                        }elseif($storUnit->pieces_num > $orderUnit->pieces_num){
                            $orderQty=$qty/$storUnit->pieces_num;
                        }else{
                            $orderQty=$qty * $orderUnit->pieces_num;
                        }   
                    }
                }
                $storeqty = $storeFrom->qty - $storeFrom->sale_count;
                $storeFrom->qty -= $orderQty;
                $storeFrom->save();
                if($storeqty<$orderQty)throw new \Exception('الكمية غير متاحة بالمخزن المحول منه الكمية الموجودة بالمخزن هى '.$storeqty);
                $storeTo = ProductsStore::where('product_id',$productid)->where('store_id',$to)->first();


                $unit_id = $inputs['store_unit'][$key];
                $orderQty=$qty;
                if($unit_id && !empty($unit_id)){
                    $prodstorUnit = $storeTo->unit_id;
                    $storUnit = ProductStoreUnit::where('product_id',$productid)->where('unit_id',$prodstorUnit)->first();

                    if($prodstorUnit != $unit_id){
                        $orderUnit = ProductStoreUnit::where('product_id',$productid)->where('unit_id',$unit_id)->first();
                        if($storUnit->pieces_num == $orderUnit->pieces_num){
                            $orderQty=$qty;
                        }elseif($storUnit->pieces_num > $orderUnit->pieces_num){
                            $orderQty=$qty/$storUnit->pieces_num;
                        }else{
                            $orderQty=$qty * $orderUnit->pieces_num;
                        }   
                    }
                }
                $storeTo->qty += $orderQty;
                $storeTo->save();
                Transit::create([
                    'product_id'=>$productid,
                    'from_store_id'=>$from,
                    'to_store_id'=>$to,
                    'qty'=>$qty
                  ] );
        }
        $request->session()->flash('alert-success','تم تحويل الكمية بنجاح');
            DB::commit();
        }catch(\Exception $e){
             DB::rollback();
             //var_dump($e->getMessage());die;
            $request->session()->flash('alert-danger', 'حدث خطأ أثناء العملية من فضلك تأكد من المخزن المحول منه واليه والكمية');
        }
        return redirect('transit');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
    }
}
