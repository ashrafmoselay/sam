<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shoraka;
class ShorakaController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $title = "Shoraka Index";
        $list = Shoraka::sortable()->Paginate(\config('custom-setting.page_size'));
        return view('shoraka.index',compact('title','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'shoraka | Create';
        return view('shoraka.create',compact('title'));
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
        Shoraka::create($inputs);
        return redirect('shoraka');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Shoraka::with('admin/Shoraka')->findOrFail($id);
        $title = 'shoraka | '.$category->name; 
        return view('admin.Shoraka.show',compact('shoraka','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $item = Shoraka::findOrFail($id);
         $title = 'shoraka | '.$item->title; 
         return view('shoraka.edit',compact('item','title'));
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
        $Shoraka = Shoraka::find($id);
        $inputs = $request->all();
        $Shoraka->update($inputs);
        return redirect('shoraka');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Shoraka::find($id)->delete();
       return redirect('shoraka');
    }
    public function search($term){
        return Shoraka::search($term);
    }
}
