<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class ClientPayments extends Model
{
    
    protected $table = 'client_payments'; 
    protected $fillable = [
        'client_id', 'total', 'paid','due','created_at'
    ]; 
    public function client(){
    	return $this->belongsTo('\App\Clients','client_id','id');
    }
    public function ScopeSearch($query,$from,$to,$client){
        if($client){
            $query = $query->where('client_id',$client);
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
        $client = \Request::get("client");
        if($client){
            $query = $query->where('client_id',$client);
        }
        if(!empty($from) && !empty($to)){
            return $query->whereBetween('created_at', array($from, $to))->orderBy('created_at','DESC')->get();
        }elseif(!empty($from)){
             return $query->where('created_at','>=',$from)->orderBy('created_at','DESC')->get();
        }elseif(!empty($to)){
             return $query->where('created_at','<=',$to)->orderBy('created_at','DESC')->get();
        }else{
            return $query->orderBy('created_at','DESC')->get();
        }
    }
}
