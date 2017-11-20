<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transit extends Model
{
    protected $table = 'products_transit'; 
    protected $fillable = [
        'product_id','from_store_id','to_store_id','qty'
    ];
}
