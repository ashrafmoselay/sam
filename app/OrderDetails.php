<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class OrderDetails extends Model
{
    protected $table = 'order_detailes'; 
    protected $fillable = [
        'order_id','product_id','qty','cost','price','total','created_at','store_id','unit_id'
    ];

    public function product(){
        return $this->belongsTo('\App\Products','product_id','id');
    }
    public function store(){
        return $this->belongsTo('\App\Store','store_id','id');
    }
    public function unit(){
        return $this->belongsTo('\App\Unit','unit_id','id');
    }
    public function invoice(){
    	return $this->belongsTo('\App\Orders','order_id','id');
    }
    public function getCostAttribute($value)
    {
        return round($value,2);
    }
}
