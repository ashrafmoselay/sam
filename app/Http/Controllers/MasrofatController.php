<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Masrofat;
class MasrofatController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->get('sort')){
            $list = Masrofat::sortable()->Paginate(\config('custom-setting.page_size'));
        }else{
            $list = Masrofat::sortable()->orderBy('id','DESC')->Paginate(\config('custom-setting.page_size'));

        }
        $title = "Masrofat Index";
        return view('masrofat.index',compact('title','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'masrofat | Create';
        return view('masrofat.create',compact('title'));
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
        Masrofat::create($inputs);
        return redirect('masrofat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Masrofat::with('admin/Masrofat')->findOrFail($id);
        $title = 'masrofat | '.$category->name; 
        return view('admin.Masrofat.show',compact('masrofat','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $item = Masrofat::findOrFail($id);
         $title = 'masrofat | '.$item->title; 
         return view('masrofat.edit',compact('item','title'));
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
        $Masrofat = Masrofat::find($id);
        $inputs = $request->all();
        $Masrofat->update($inputs);
        return redirect('masrofat');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Masrofat::find($id)->delete();
       return redirect('masrofat');
    }
     public function search(Request $request,$term=''){
        $sharek_id = $request->get('sharek_id');
        if(!empty($sharek_id)){
            if($sharek_id=='g'){
                $list =  Masrofat::where('type',1)->search($request->get('from'),$request->get('to'),$term);
            }else{
                $list =  Masrofat::where([['type',2],['sharek_id',$sharek_id]])->search($request->get('from'),$request->get('to'),$term);
            }
        }else{
            //dd('kk');
             $list =  Masrofat::search($request->get('from'),$request->get('to'),$term);
        }
        if($request->ajax()){
            return \View::make('masrofat._list',compact('list'));
        }else{ 
            $title = 'masrofat | Create';
            return view('masrofat.index',compact('title','list'));
        }
    }
}
