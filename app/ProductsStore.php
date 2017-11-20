<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ProductsStore extends Model
{
    use Sortable;
    public $sortable = [
                        'id',
                        'ptitle',
                        'qty',
                        'sale_count',
                        'unit_id',
                        'cost'
                        ];
    protected $table = 'products_store'; 
    protected $fillable = [
        'product_id','store_id','qty','sale_count','unit_id','cost'
    ];

    protected function ptitleSortable($query, $order) {
        return $query->join('products','product_id','products.id')->selectRaw('products_store.*')->orderBy('title', $order);
    }
    /*protected function costSortable($query, $order) {
        return $query->join('products','product_id','products.id')->selectRaw('products_store.*')->orderBy('cost', $order);
    }*/
    public function store(){
        return $this->belongsTo('\App\Store','store_id','id');
    }
    public function product(){
        return $this->belongsTo('\App\Products','product_id','id');
    }
    public function unit(){
        return $this->belongsTo('\App\Unit','unit_id','id');
    }
}
