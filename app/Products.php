<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use DB;
class Products extends Model
{
    
    use Sortable;
    protected $table = 'products'; 
    protected $fillable = [
        'title','description','last_avg_cost','code','category_id','category2_id','observe','model'
    ];
    public $sortable = [
                        'id',
                        'title',
                        'category_id',
                        'category2_id',
                        'code',
                        'last_avg_cost',
                        'total',
                        'created_at',
                        'code',
                        'model'
                        ];
    public function category(){
        return $this->belongsTo('\App\Category','category_id','id');
    }
    public function unit(){
        return $this->hasMany('\App\ProductStoreUnit','product_id','id');
    }

    public function category2(){
        return $this->belongsTo('\App\Category','category2_id','id');
    }
    public function ScopeSearch($query,$search,$category_id){
        $observe = \Config::get('custom-setting.observe');
        $size = (\Request::get("page_size"))?\Request::get("page_size"):\config('custom-setting.page_size');
        $cat2 = \Request::get("category2_id");
        if($category_id){
            if($category_id == "observe"){
                $query->leftjoin(DB::raw('(SELECT  sale_count,product_id,sum(qty) as qty FROM products_store group by product_id ) s'), function($join){
                            $join->on('s.product_id', '=', 'products.id');
                        })->whereRaw(DB::raw('qty-sale_count <= observe '));
                //$query->whereRaw('quantity-sale_count <='.$observe." OR quantity-sale_count = observe");
            }else{
                $query->where('category_id',$category_id);
            }
        }
        if($cat2){
            $query->where('category2_id',$cat2);
        }
        if($search){
             $query->where('title','like',"%$search%")->orWhere('code','like',"%$search%")->orWhere('model','like',"%$search%")->orWhere('id','=',"$search");
        }
        $query->sortable()->orderBy('id','DESC');
        //dd($query->toSql());
        return $query->Paginate($size);
          
    }
    //Defining An Accessor
    public function getTitleAttribute($value)
    {
        return ucfirst($value);
    }
    public function getCostAttribute($value)
    {
        return round($value,2);
    }
    //Defining A Mutator
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = strtolower($value);
    }
    
    function decToFraction($float) {
        // 1/2, 1/4, 1/8, 1/16, 1/3 ,2/3, 3/4, 3/8, 5/8, 7/8, 3/16, 5/16, 7/16,
        // 9/16, 11/16, 13/16, 15/16
        $whole = floor ( $float );
        $decimal = $float - $whole;
        $leastCommonDenom = 48; // 16 * 3;
        $denominators = array (2, 3, 4, 8, 16, 24, 48 );
        $roundedDecimal = round ( $decimal * $leastCommonDenom ) / $leastCommonDenom;
        if ($roundedDecimal == 0)
            return $whole;
        if ($roundedDecimal == 1)
            return $whole + 1;
        foreach ( $denominators as $d ) {
            if ($roundedDecimal * $d == floor ( $roundedDecimal * $d )) {
                $denom = $d;
                break;
            }
        }
        return ($whole == 0 ? '' : $whole) . " " . ($roundedDecimal * $denom) . "/" . $denom;
    }
}
