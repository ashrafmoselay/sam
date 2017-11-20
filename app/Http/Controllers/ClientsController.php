<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Clients;
use App\ClientPayments;
use DB;
class ClientsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->get('sort')){
            $list = Clients::sortable()->Paginate(\config('custom-setting.page_size'));
        }else{
            $list = Clients::sortable()->orderBy('id','DESC')->Paginate(\config('custom-setting.page_size'));

        }
        $title = "Clients Index";
        return view('clients.index',compact('title','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Clients | Create';
        return view('clients.create',compact('title'));
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
        Clients::create($inputs);
        if($request->ajax()){
            return view('clients.dropdown');
        }
        return redirect('clients');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Clients::with('installment')->findOrFail($id);
        $title = 'Clients | '.$client->name; 
        return view('clients.show',compact('client','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $item = Clients::findOrFail($id);
         $title = 'Clients | '.$item->title; 
         return view('clients.edit',compact('item','title'));
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
        $Clients = Clients::find($id);
        $inputs = $request->all();
        $Clients->update($inputs);
        return redirect('clients');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $client = Clients::find($id);
       if(count($client->orders)==0 && count($client->installment)==0){
            $client->delete();
        }
        return (count($client->orders)>0)?$client->orders:$client->installment;
       //return redirect('Clients');
    }
    public function pay($id){
        $client = Clients::find($id);
        $title = 'Clients | Payments';
        return view('clients.payments',compact('title','client'));
    }
    public function addpay(Request $request)
    {
        try{
            DB::beginTransaction();
            $inputs = $request->except('_token');
            //var_dump($inputs);die;
            ClientPayments::create($inputs);
            $request->session()->flash('alert-success', 'تمت عملية الدفع بنجاح');
            $client = Clients::find($inputs['client_id']);
            $client->paid += $inputs['paid'];
            $client->due -= $inputs['paid'];
            $client->save();
            DB::commit();
        }catch(\Exception $e){
             DB::rollback();
            $request->session()->flash('alert-danger', 'Some Error was ocuured during adding! '.$e->getMessage());
        }
        return redirect('clients');
    } 
    public function search(Request $request,$term=''){
        $list =  Clients::search($term);
        if($request->ajax()){
        return \View::make('clients._list',compact('list'));
        }else{
            $title = 'Clients | Create';
            return view('clients.index',compact('title','list'));
        }
    }
    public function payments(Request $request){
        
        $title = trans("app.Clients Payments");
        $list = ClientPayments::search($request->get('from'),$request->get('to'),$request->get('client'));
        if($request->ajax()){
            return \View::make('clientPayments._list',compact('list'));
        }else{ 
            return view('clientPayments.index',compact('title','list'));
        }
    } 
}
