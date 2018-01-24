<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Kyslik\ColumnSortable\Sortable;
 
class Cheq extends Model
{
    use Sortable;
    protected $table = 'cheq'; 
    protected $fillable = [
        'bank_id','supplier_id','cheq_num','value','date','auto','note','is_paid'
    ];
    public $sortable = [
                        'id',
	                    'bank_id',
	                    'supplier_id',
	                    'cheq_num',
	                    'value',
	                    'date',
                        'created_at',
                        ];

    public function bank(){
        return $this->belongsTo('\App\Bank','bank_id','id');
    }
    public function supplier(){
        return $this->belongsTo('\App\Suppliers','supplier_id','id');
    }
    public function ScopeSearch($query){
	    $from = \Request::get("from");
	    $to = \Request::get("to");
	    $term = \Request::get("term");
	    $bank = \Request::get("bank_id");
	    $supplier = \Request::get("supplier_id");
	    if($bank){
		    $query->where('bank_id',$bank);
	    }
	    if($supplier){
		    $query->where('supplier_id',$supplier);
	    }
	    if($term){
		    $query->where('cheq_num',"LIKE", "%$term%");
	    }
        $size = (\Request::get("page_size"))?\Request::get("page_size"):\config('custom-setting.page_size');
        if(!empty($from) && !empty($to)){
	        $query->whereBetween('date', array($from, $to))->orderBy('date','DESC');
        }elseif(!empty($from)){
	        $query->where('date','>=',$from)->orderBy('date','DESC');
        }elseif(!empty($to)){
	        $query->where('date','<=',$to)->orderBy('date','DESC');
        }
        return $query->Paginate($size);
    }

    //Defining An Accessor
    public function getNameAttribute()
    {
        $name = "";
        $value = $this->attributes['user_type'];
        if($value==1){
            $name = $this->client->name;
        }elseif($value==2){
            $name = $this->supplier->name;
        }elseif($value==3){
            $name = $this->partner->name;
        }else{
            return "عام";
        }
        return $name;
    }
    public function getUserTypeAttribute($value)
    {
        $type= "عام";
       
         //return $value;  
        if($value==1){
            $type= trans('app.Clients');
        }elseif($value==2){
            $type= trans('app.Suppliers');
        }elseif($value==3){
            $type= trans('app.Shoraka');
        }
        return $type;
    }
    public function getTypeAttribute($value)
    {
        $type= "";
        // return $value; 
        if($value==1){
            $type= trans('app.withdraw');
        }elseif($value==2){
            $type= trans('app.deposite');
        }
        return $type;
    }
    public function getCreatedAtAttribute($value)
    { 
        return date('Y-m-d', strtotime($value));
    }
    
}
