<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Clients extends Model
{
   
    use Sortable;
    protected $table = 'clients'; 
    protected $fillable = [
        'name','mobile', 'total', 'paid','due','qest_value','type'
    ];
    public $sortable = [
                        'id',
                        'name',
                        'total',
                        'paid',
                        'due',
                        'qest_value',
                        'created_at',
                        'updated_at'];
    public function ScopeSearch($query,$search){
        $size = (\Request::get("page_size"))?\Request::get("page_size"):\config('custom-setting.page_size');
		return $query->where('name','like',"%$search%")->orwhere('id','=',"$search")->Paginate($size);
    }
    public function installment(){
        return $this->hasMany('\App\ClientPayments','client_id','id');
    }
    public function orders(){
        return $this->hasMany('\App\Orders','client_id','id');
    }

    public function treasury(){
        return $this->hasMany('\App\TreasuryMovement','client_id','id')->where('user_type','=', 1);
    }
    public function returns(){
        return $this->hasMany('\App\OrdersReturns','client_id','id');
    }
}
