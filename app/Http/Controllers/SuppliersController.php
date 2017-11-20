<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Suppliers;
use DB;
use App\SupplierPayments;
class SuppliersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->get('sort')){
            $list = Suppliers::sortable()->Paginate(\config('custom-setting.page_size'));
        }else{
            $list = Suppliers::sortable()->orderBy('id','DESC')->Paginate(\config('custom-setting.page_size'));

        }
        $title = "Suppliers Index";
        return view('suppliers.index',compact('title','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Suppliers | Create';
        return view('suppliers.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        Suppliers::create($inputs);
        if($request->ajax()){
            return view('suppliers.dropdown');
        }
        return redirect('suppliers');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $suppliers = Suppliers::with('installment')->findOrFail($id);
        $title = 'Suppliers | '.$suppliers->name; 
        return view('suppliers.show',compact('suppliers','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $item = Suppliers::findOrFail($id);
         $title = 'Suppliers | '.$item->title; 
         return view('suppliers.edit',compact('item','title'));
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
        $Suppliers = Suppliers::find($id);
        $inputs = $request->all();
        $Suppliers->update($inputs);
        return redirect('suppliers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $supplier = Suppliers::find($id);
       if(count($supplier->invoices)==0 && count($supplier->installment)==0){
            $supplier->delete();
        }
        return (count($supplier->invoices)>0)?$supplier->invoices:$supplier->installment;
       //return redirect('suppliers');
    }
    public function pay($id){
        $supplier = Suppliers::find($id);
        $title = 'Suppliers | Payments';
        return view('suppliers.payments',compact('title','supplier'));
    }
    public function addpay(Request $request)
    {
        try{
            DB::beginTransaction();
            $inputs = $request->except('_token');
            //var_dump($inputs);die;
            SupplierPayments::create($inputs);
            $request->session()->flash('alert-success', 'Supplier Payement was successful added!');
            $supplier = Suppliers::find($inputs['supplier_id']);
            $supplier->paid += $inputs['paid'];
            $supplier->due -= $inputs['paid'];
            $supplier->save();
            DB::commit();
        }catch(\Exception $e){
             DB::rollback();
            // dd($e->getMessage());die;
            $request->session()->flash('alert-danger', 'Some Error was ocuured during adding! '.$e->getMessage());
        }
        return redirect('suppliers');
    } 
    public function payments(Request $request){
        
        $title = trans("app.Supplier Payments"); 
        $list = SupplierPayments::search($request->get('from'),$request->get('to'),$request->get('supplier_id'));
        if($request->ajax()){
            return \View::make('supplierPayments._list',compact('list'));
        }else{ 
            return view('supplierPayments.index',compact('title','list'));
        } 
    } 
    public function search(Request $request){
        $term = $request->get('term');
        //dd($term);
        $list =  Suppliers::search($term);
        if($request->ajax()){
        return \View::make('suppliers._list',compact('list'));
        }else{
            $title = 'suppliers | Create';
            return view('suppliers.index',compact('title','list'));
        }
    }
}
