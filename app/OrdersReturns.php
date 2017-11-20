<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class OrdersReturns extends Model
{
    
    use Sortable;
    protected $table = 'orders_returns'; 
    protected $fillable = [
        'client_id','total','is_subtract','created_at','store_id'
    ];
    public $sortable = [
                        'id',
                        'client_id',
                        'total',
                        'created_at',
                        'updated_at'];

    public function details(){
        return $this->hasMany('\App\OrderReturnDetails','return_id','id')->orderBy('id','DESC');
    }
    public function client(){
        return $this->belongsTo('\App\Clients','client_id','id');
    } 
    public function store(){
        return $this->belongsTo('\App\Store','store_id','id');
    }                
    public function ScopeSearch($query,$from,$to){
        
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
    public function getCreatedAtAttribute($value)
    { 
        return date('Y-m-d', strtotime($value));
    }
}
