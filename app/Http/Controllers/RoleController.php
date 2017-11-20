<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
class RoleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $title = "Role Index";
        $list = Role::get();
        return view('roles.index',compact('title','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Role | Create';
        $item = new Role;
        $data = \App\Permission::getRoutePermission();
        $controllers = $data['controllers'];
        $methods = $data['methods'];
        $rolperm = [];
        return view('roles.create',compact('title','item','methods','controllers'));
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
        $id = Role::create($inputs)->id;
        foreach($inputs['perm'] as $key=>$perm){
            $perID = \App\Permission::where('name',$key)->first()->id;
            $newitem = new \App\PermissionRole;
            $newitem->permission_id=$perID;
            $newitem->role_id=$id;
            $newitem->save();
        }
        return redirect('role');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Role = Role::with('admin/Role')->findOrFail($id);
        $title = 'Role | '.$Role->name; 
        return view('roles.show',compact('Role','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Role::findOrFail($id);
        $title = 'Role | '.$item->title;
        $data = \App\Permission::getRoutePermission();
        $controllers = $data['controllers'];
        $methods = $data['methods'];
        $rolperm = [];
        foreach($item->permision as $v){
            $rolperm[]=$v->perm->name;
        }

        return view('roles.edit',compact('item','title','controllers','methods','rolperm','perm'));
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
        $Role = Role::find($id);
        $inputs = $request->all();
        $Role->update($inputs);
        \DB::table('permission_role')
            ->where('role_id', $id)
            ->delete();
        //dd($inputs['perm']);
        foreach($inputs['perm'] as $key=>$perm){
            $perID = \App\Permission::where('name',$key)->first()->id;
            $newitem = new \App\PermissionRole;
            $newitem->permission_id=$perID;
            $newitem->role_id=$id;
            $newitem->save();
        }
        return redirect('role');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $Role = Role::find($id);
       if(count($Role->products)==0){
            $Role->delete();
        }
        return $Role->products;
    }
     public function search(Request $request,$term=''){
        $sharek_id = $request->get('sharek_id');
        if(!empty($sharek_id)){
            if($sharek_id=='g'){
                $list =  Role::where('type',1)->search($request->get('from'),$request->get('to'),$term);
            }else{
                $list =  Role::where([['type',2],['sharek_id',$sharek_id]])->search($request->get('from'),$request->get('to'),$term);
            }
        }else{
            //dd('kk');
             $list =  Role::search($request->get('from'),$request->get('to'),$term);
        }
        if($request->ajax()){
            return \View::make('roles._list',compact('list'));
        }else{ 
            $title = 'Role | Create';
            return view('roles.index',compact('title','list'));
        }
    }
}
