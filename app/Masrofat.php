<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Masrofat extends Model
{
   
    use Sortable;
    protected $table = 'masrofat'; 
    protected $fillable = [
        'name','value','type','sharek_id'
    ];
    public $sortable = [
                        'id',
                        'name',
                        'value',
                        'type',
                        'sharek_id',
                        'created_at',
                        ];
                        
    public function ScopeSearch($query,$from,$to,$term){
        if(!empty($term))
            $query->where('name','like',"%$term%");
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

    public function sharek(){
        return $this->belongsTo('\App\Shoraka','sharek_id','id');
    }
    //Defining An Accessor
    public function getTypeAttribute($value)
    {
        return ($value==1)?trans('app.general'):trans('app.special');
    }
    public function getCreatedAtAttribute($value)
    { 
        return date('Y-m-d', strtotime($value));
    }
}
