<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Kyslik\ColumnSortable\Sortable;
 
class Transaction extends Model
{
    use Sortable;
    protected $table = 'transactions'; 
    protected $fillable = [
	    'bank_id',
	    'op_date',
	    'value',
	    'type',
	    'total',
	    'due',
	    'note'
    ];
    public $sortable = [
                        'id',
                        'bank_id',
                        'op_date',
                        'value',
                        'type',
	                    'note'
                        ];

    public function bank(){
        return $this->belongsTo('\App\Bank','bank_id','id');
    }
    public function ScopeSearch($query,$from,$to,$type){
        if($type){
            $query = $query->where('type',$type);
        }
        $size = (\Request::get("page_size"))?\Request::get("page_size"):\config('custom-setting.page_size');
        if(!empty($from) && !empty($to)){
            return $query->whereBetween('created_at', array($from, $to))->orderBy('created_at')->Paginate($size);
        }elseif(!empty($from)){
             return $query->where('created_at','>=',$from)->orderBy('created_at')->Paginate($size);
        }elseif(!empty($to)){
             return $query->where('created_at','<=',$to)->orderBy('created_at')->Paginate($size);
        }else{
            return $query->Paginate($size);
        }
    }

    //Defining An Accessor
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
