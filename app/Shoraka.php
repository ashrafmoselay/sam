<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Shoraka extends Model
{
   
    use Sortable;
    protected $table = 'shoraka'; 
    protected $fillable = [
        'name','total','profit_percent'
    ];
    public $sortable = [
                        'id',
                        'name',
                        'profit_percent',
                        'created_at',
                        'mobile'];
}
