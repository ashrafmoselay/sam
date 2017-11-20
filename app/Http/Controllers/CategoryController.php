<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $title = "Category Index";
        $list = Category::sortable()->Paginate(\config('custom-setting.page_size'));
        return view('category.index',compact('title','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'category | Create';
        return view('category.create',compact('title'));
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
        Category::create($inputs);
        return redirect('category');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::with('admin/Category')->findOrFail($id);
        $title = 'category | '.$category->name; 
        return view('admin.Category.show',compact('category','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $item = Category::findOrFail($id);
         $title = 'category | '.$item->title; 
         return view('category.edit',compact('item','title'));
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
        $Category = Category::find($id);
        $inputs = $request->all();
        $Category->update($inputs);
        return redirect('category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $category = Category::find($id);
       if(count($category->products)==0){
            $category->delete();
        }
        return $category->products;
    }
     public function search(Request $request,$term=''){
        $sharek_id = $request->get('sharek_id');
        if(!empty($sharek_id)){
            if($sharek_id=='g'){
                $list =  Category::where('type',1)->search($request->get('from'),$request->get('to'),$term);
            }else{
                $list =  Category::where([['type',2],['sharek_id',$sharek_id]])->search($request->get('from'),$request->get('to'),$term);
            }
        }else{
            //dd('kk');
             $list =  Category::search($request->get('from'),$request->get('to'),$term);
        }
        if($request->ajax()){
            return \View::make('category._list',compact('list'));
        }else{ 
            $title = 'category | Create';
            return view('category.index',compact('title','list'));
        }
    }
}
