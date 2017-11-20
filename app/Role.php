<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;
use Klaravel\Ntrust\Traits\NtrustRoleTrait;

class Role extends Model
{
    use NtrustRoleTrait;

    protected $fillable = [
        'name', 'display_name', 'description'];
    /*
     * Role profile to get value from ntrust config file.
     */
    protected static $roleProfile = 'user';

    public function permision(){
    	return $this->hasMany('\App\PermissionRole','role_id','id');
    }
}