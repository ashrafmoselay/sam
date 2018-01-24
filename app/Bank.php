<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Bank extends Model
{
    use Sortable;
    protected $table = 'bank'; 
    protected $fillable = ['name','number','balance'];
    public $sortable = [
                        'id',
                        'name',
			'number',
			'balance'
                        ];

    public function getCreatedAtAttribute($value)
    { 
        return date('Y-m-d', strtotime($value));
    }

}
