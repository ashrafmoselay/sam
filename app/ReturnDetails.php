<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class ReturnDetails extends Model
{
    use Sortable;
    protected $table = 'return_details'; 
    protected $fillable = [
        'return_id','product_id','qty','cost','total','unit_id'
    ];

    public function product(){
    	return $this->belongsTo('\App\Products','product_id','id');
    }
    public function invoice(){
    	return $this->belongsTo('\App\Returns','return_id','id');
    }
    public function unit(){
        return $this->belongsTo('\App\Unit','unit_id','id');
    }
    public function getCostAttribute($value)
    {
        return round($value,2);
    }
}
