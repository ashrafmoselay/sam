<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bank;
use App\Transaction;
class BankController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $title = "Bank Index";
        $list = Bank::sortable()->Paginate(\config('custom-setting.page_size'));
        return view('bank.index',compact('title','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Bank | Create';
        $item = new Bank;
        return view('bank.create',compact('title','item'));
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
        Bank::create($inputs);
        return redirect('bank');
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
         $item = Bank::findOrFail($id);
         $title = 'Bank | '.$item->title; 
         return view('bank.edit',compact('item','title'));
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
        $Record = Bank::find($id);
        $inputs = $request->all();
        $Record->update($inputs);
        return redirect('bank');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $first =  Bank::first();
       $prodRecord = ProductsBank::where('Record_id',$id)->first();
       if($id != $first->id && count($prodRecord)==0){
            Bank::find($id)->delete(); 
            return 0;
       }
        return 1;     
       //return redirect('Record');
    }


    public function withdraw($id)
    {
       $item = Bank::find($id);
       return view('bank.withdraw',compact('item','id'));
    }

    public function withdrawsave(Request $request)
    {
        $inputs = $request->all();
        $item = Bank::find($inputs['bank_id']);
        //dd($inputs);
        if(count($item) && $inputs['type']==1){
            $item->balance -= $inputs['value'];
            $item->save();
	        Transaction::create($inputs);
        }else{
	        $item->balance += $inputs['value'];
	        $item->save();
	        Transaction::create($inputs);
        }
        return redirect('bank');
    }
    public function show($id)
    {
	    $size= config('custom-setting.page_size');
        $list = Transaction::where('bank_id',$id)->paginate($size);
        //dd($list[0]->bank);
       return view('bank.transaction',compact('list'));
    }


}
