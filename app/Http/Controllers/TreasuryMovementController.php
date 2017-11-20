<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TreasuryMovement;
use App\Clients;
use App\Suppliers;
use App\Shoraka;
use App\Masrofat;
use Config;
use File;
class TreasuryMovementController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->get('sort')){
            $list = TreasuryMovement::sortable()->Paginate(\config('custom-setting.page_size'));
        }else{
            $list = TreasuryMovement::sortable()->orderBy('id','DESC')->Paginate(\config('custom-setting.page_size'));
        }
        $title = "TreasuryMovement Index";
        return view('treasury.index',compact('title','list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'TreasuryMovement | Create';
        return view('treasury.create',compact('title'));
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
        TreasuryMovement::create($inputs);
        $ut = $inputs['user_type'];
        $t = $inputs['type'];
        if($ut==1){
            $client = Clients::find($inputs['client_id']);
            if($t==1){
                $client->total += $inputs['value'];
                $client->due += $inputs['value'];         
            }else{
                $client->due -= $inputs['value'];  
                $client->paid += $inputs['value'];     
            }
            $client->save();
        }elseif($ut==2){
            $supplier = Suppliers::find($inputs['supplier_id']);
            if($t==1){
                $supplier->due -= $inputs['value'];  
                $supplier->paid += $inputs['value'];        
            }else{
                $supplier->total += $inputs['value'];
                $supplier->due += $inputs['value'];      
            }
            $supplier->save();

        }/*elseif($ut==3){
            $partner = Shoraka::find($inputs['partner_id']);
            if($t==1){     
                $minputs['name']=$inputs['title'];
                $minputs['type']=2;
                $minputs['sharek_id']=$inputs['partner_id'];
                $minputs['value']=$inputs['value'];
                Masrofat::create($minputs);
            }else{
                $partner->total += $inputs['value'];
                $first_balance = Config::get('custom-setting.current_balance');
                $total = $first_balance + $inputs['value'];
                $this->updateBalance($total);    
            }
            $partner->save();

        }*/
        return redirect('treasury');
    }

    public function updateBalance($value) {
        $lines = file(config_path().'/custom-setting.php');
        $data ="";
        $str = 'current_balance';
        foreach ($lines as $lineNumber => $line) {
            if (strpos($line, $str) !== false) {
                $line = "'current_balance' => '".$value."',\n";
            }
            $data .= $line;
        }
        $contents = File::put(config_path().'/custom-setting.php',$data);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = TreasuryMovement::with('admin/TreasuryMovement')->findOrFail($id);
        $title = 'TreasuryMovement | '.$category->name; 
        return view('admin.treasury.show',compact('TreasuryMovement','title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $item = TreasuryMovement::findOrFail($id);
         $title = 'TreasuryMovement | '.$item->title; 
         return view('treasury.edit',compact('item','title'));
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
        $TreasuryMovement = TreasuryMovement::find($id);
        $inputs = $request->all();

        $client = Clients::find($inputs['client_id']);
        $client->total -= $TreasuryMovement->value;
        $client->save();
        $TreasuryMovement->update($inputs);

        $client = Clients::find($inputs['client_id']);
        $client->total += $inputs['value'];
        $client->save();
        return redirect('treasury');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       TreasuryMovement::find($id)->delete();
       return redirect('TreasuryMovement');
    }
     public function search(Request $request){
        $type = $request->get('type');
        $list =  TreasuryMovement::search($request->get('from'),$request->get('to'),$type);
        if($request->ajax()){
            return \View::make('treasury._list',compact('list'));
        }else{ 
            $title = 'TreasuryMovement | Create';
            return view('treasury.index',compact('title','list'));
        }
    }
}
