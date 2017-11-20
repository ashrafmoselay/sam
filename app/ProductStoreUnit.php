<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 
class ProductStoreUnit extends Model
{
    protected $table = 'product_store_unit'; 
    protected $fillable = [
        'pieces_num','unit_id','product_id','cost_price','sale_price','default_sale','default_purchase','price2','price3'
    ];
    
    public function unit(){
        return $this->belongsTo('\App\Unit','unit_id','id');
    }
    public function product(){
        return $this->belongsTo('\App\Products','unit_id','id');
    }
}
