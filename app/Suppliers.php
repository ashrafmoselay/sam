<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Suppliers extends Model
{
   
    use Sortable;
    protected $table = 'suppliers'; 
    protected $fillable = [
        'name','mobile','total', 'paid','due'
    ];
    public $sortable = [
                        'id',
                        'name',
                        'created_at',
                        'mobile',
                        'total',
                        'paid',
                        'due',
                        'qest_value',
                        ];

    public function installment(){
        return $this->hasMany('\App\SupplierPayments','supplier_id','id');
    }
    public function invoices(){ 
        return $this->hasMany('\App\PurchaseInvoice','supplier_id','id');
    }
    public function ScopeSearch($query,$search){
        $size = (\Request::get("page_size"))?\Request::get("page_size"):\config('custom-setting.page_size');
        return $query->where('name','like',"%$search%")->orwhere('mobile','like',"%$search%")->Paginate($size);
    }
}
