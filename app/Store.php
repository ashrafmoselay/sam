<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Store extends Model
{
   
    use Sortable;
    protected $table = 'store'; 
    protected $fillable = [
        'address','mobile','note'
    ];
    public $sortable = [
                        'id',
                        'address',
                        'mobile',
                        'note',
                        ];
    
    public function products(){
        return $this->hasMany('\App\ProductsStore','store_id','id');
    }

}
