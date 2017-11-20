<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
	protected $table = "permission_role";
	protected $fillable = ['role_id', 'permission_id'];
	public $timestamps = false;
	
    public function perm(){
        return $this->belongsTo('\App\Permission','permission_id','id');
    } 
    /*
     * Role profile to get value from ntrust config file.
     */
}