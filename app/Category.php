<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Kyslik\ColumnSortable\Sortable;
 
class Category extends Model
{
    
    use Sortable;
    protected $table = 'category'; 
    protected $fillable = [
        'name','type'
    ];
    public $sortable = [
                        'id',
                        'name',
                        'type'
                        ];

    public function products(){
        return $this->hasMany('\App\Products','category_id','id');
    }
    public function products2(){
        return $this->hasMany('\App\Products','category2_id','id');
    }
}
