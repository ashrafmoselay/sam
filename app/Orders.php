<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Orders extends Model
{
    use Sortable;
    protected $table = 'orders'; 
    protected $fillable = [
        'client_id','payment_type','is_paid','total','paid','due','created_at','discount','note','id','discount_type','bank_id'
    ];
    public $sortable = [
                        'id',
                        'client_id',
                        'payment_type',
                        'total',
                        'paid',
                        'due',
                        'discount',
                        'created_at',
                        'updated_at'];

    public function details(){
        return $this->hasMany('\App\OrderDetails','order_id','id');
    }
    public function client(){
    	return $this->belongsTo('\App\Clients','client_id','id');
    }
    public function ScopeSearch($query,$from,$to,$client){
        if($client){
            $query = $query->where('client_id',$client);
        }
        $search = \Request::get("search");
        if($search){
            $query->orwhere('id','=',"$search")->orwhere('client_id','=',"$search");
        }
        $size = (\Request::get("page_size"))?\Request::get("page_size"):\config('custom-setting.page_size');
        if(!empty($from) && !empty($to)){
            return $query->whereBetween('created_at', array($from, $to))->orderBy('created_at','DESC')->Paginate($size);
        }elseif(!empty($from)){
             return $query->where('created_at','>=',$from)->orderBy('created_at','DESC')->Paginate($size);
        }elseif(!empty($to)){
             return $query->where('created_at','<=',$to)->orderBy('created_at','DESC')->Paginate($size);
        }else{
            return $query->orderBy('id','DESC')->Paginate($size);
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
    //Defining An Accessor
    public function getPaymentTypeAttribute($value)
    {
        $payment = '';
        if($value==1){
            $payment = trans('app.Cash Payment');
        }elseif($value==2){
            $payment = trans('app.Payment in installments');
        }else{
            $payment = 'فيزا';
        }
        return $payment;
    }
    public function getCreatedAtAttribute($value)
    { 
        return date('Y-m-d', strtotime($value));
    }
}
