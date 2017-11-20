<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Kyslik\ColumnSortable\Sortable;
 
class TreasuryMovement extends Model
{
    use Sortable;
    protected $table = 'treasury_movement'; 
    protected $fillable = [
        'title','client_id','value','type','user_type','supplier_id','partner_id'
    ];
    public $sortable = [
                        'id',
                        'title',
                        'client_id',
                        'value',
                        'created_at',
                        ];

    public function client(){
        return $this->belongsTo('\App\Clients','client_id','id');
    }
    public function supplier(){
        return $this->belongsTo('\App\Suppliers','supplier_id','id');
    }
    public function partner(){
        return $this->belongsTo('\App\Shoraka','partner_id','id');
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
