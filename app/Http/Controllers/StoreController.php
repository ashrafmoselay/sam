<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\ProductsStore;
use App\Products;
class StoreController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $title = "store Index";
        $list = Store::sortable()->Paginate(\config('custom-setting.page_size'));
        return view('store.index',compact('title','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'store | Create';
        $item = new Store;
        return view('store.create',compact('title','item'));
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
        Store::create($inputs);
        return redirect('store');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $firstStore = Store::first()->id;
        $item = Store::findOrFail($id);
        $storeName = $item->address;
        $list = ProductsStore::where('store_id',$id)->where('qty','!=',0)->sortable()->get();
        
        //dd($item->address);
        return view('store.show',compact('list','storeName'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $item = Store::findOrFail($id);
         $title = 'store | '.$item->title; 
         return view('store.edit',compact('item','title'));
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
        $store = Store::find($id);
        $inputs = $request->all();
        $store->update($inputs);
        return redirect('store');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $first =  Store::first();
       $prodStore = ProductsStore::where('store_id',$id)->first();
       if($id != $first->id && count($prodStore)==0){
            Store::find($id)->delete(); 
            return 0;
       }
        return 1;     
       //return redirect('store');
    }
    public function search($term){
        return Store::search($term);
    }
}
