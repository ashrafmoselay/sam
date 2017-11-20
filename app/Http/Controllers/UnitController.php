<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unit;
class UnitController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $title = "Unit Index";
        $list = Unit::Paginate(\config('custom-setting.page_size'));
        return view('unit.index',compact('title','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Unit | Create';
        return view('unit.create',compact('title'));
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
        Unit::create($inputs);
        return redirect('unit');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Unit::findOrFail($id);
        $title = 'Unit | '.$Unit->name; 
        return view('admin.unit.show',compact('item','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $item = Unit::findOrFail($id);
         $title = 'Unit | '.$item->title; 
         return view('unit.edit',compact('item','title'));
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
        $item = Unit::find($id);
        $inputs = $request->all();
        $item->update($inputs);
        return redirect('unit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $item = Unit::find($id);
       if(count($item->products)==0){
            $item->delete();
        }
        return $item->products;
    }
}
