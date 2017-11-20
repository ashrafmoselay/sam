<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 
class Unit extends Model
{
    protected $table = 'unit'; 
    protected $fillable = [
        'title'
    ];
    
    public function products(){
        return $this->hasMany('\App\ProductStoreUnit','unit_id','id');
    }
}
