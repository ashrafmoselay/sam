<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierPayments extends Model
{
    protected $table = 'supplier_payments'; 
    protected $fillable = [
        'supplier_id', 'total', 'paid','due','esal_num','created_at'
    ]; 
    public function supplier(){
    	return $this->belongsTo('\App\Suppliers','supplier_id','id');
    } 
    public function ScopeSearch($query,$from,$to,$supplier_id){
        if($supplier_id){
            $query = $query->where('supplier_id',$supplier_id);
        }
        $size = (\Request::get("page_size"))?\Request::get("page_size"):\config('custom-setting.page_size');
        if(!empty($from) && !empty($to)){
            return $query->whereBetween('created_at', array($from, $to))->orderBy('created_at')->Paginate($size);
        }elseif(!empty($from)){
             return $query->where('created_at','>=',$from)->orderBy('created_at')->Paginate($size);
        }elseif(!empty($to)){
             return $query->where('created_at','<=',$to)->orderBy('created_at')->Paginate($size);
        }else{
            return $query->Paginate($size);
        }
    }
    public function ScopeSearch2($query){
        $from = \Request::get("from");
        $to = \Request::get("to");
        $clientid = \Request::get("client");
        if(!$clientid)return;
        $client = \App\Clients::find($clientid);
        $supplier = \App\Suppliers::where('name',$client->name)->first();
        $supplier_id = ($supplier)?$supplier->id:0;
        $query = $query->where('supplier_id',$supplier_id);
        $size = (\Request::get("page_size"))?\Request::get("page_size"):\config('custom-setting.page_size');
        if(!empty($from) && !empty($to)){
            return $query->whereBetween('created_at', array($from, $to))->orderBy('created_at')->Paginate($size);
        }elseif(!empty($from)){
             return $query->where('created_at','>=',$from)->orderBy('created_at')->Paginate($size);
        }elseif(!empty($to)){
             return $query->where('created_at','<=',$to)->orderBy('created_at')->Paginate($size);
        }else{
            return $query->Paginate($size);
        }
    }
}
