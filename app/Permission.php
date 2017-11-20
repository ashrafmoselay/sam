<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Klaravel\Ntrust\Traits\NtrustPermissionTrait;
use DB;
class Permission extends Model
{
    use NtrustPermissionTrait;

    /*
     * Role profile to get value from ntrust config file.
     */
    protected static $roleProfile = 'user';

    protected static function getRoutePermission($truncate=false){
    	$role = null;
    	if($truncate){
    		DB::table('permission_role')->truncate();
        	DB::table('permissions')->truncate();
        	$role = \App\Role::find(1);
    	}
        $controllers = [];
        $disableController = [
            'PermissionController',
            //'RoleController',
            'WatchtowerController',
            'UserController',
            'LoginController',
            'RegisterController',
            'ForgotPasswordController',
            'ResetPasswordController',
            'Images'
        ];
        foreach (\Route::getRoutes() as $route)
        {
            $action = $route->getAction();

            if (array_key_exists('controller', $action))
            {
                $controllerAction = class_basename($action['controller']);
                list($controller, $method) = explode('@', $controllerAction);
                if(in_array($controller,$disableController))continue;
                if(!in_array($controller,$controllers)){
                    $controllers[] = $controller;
                    $methods[$controller] = [];
                }
                if(!in_array($method,$methods[$controller]))
                    $methods[$controller][] = $method;
            }
        }

        foreach($methods as $cont=>$operations){
            foreach($operations as $v=>$op){
                $name = $cont.'-'.$op;
                $check = \App\Permission::where('name',$name)->first();
                if(count($check)) continue;
                $perm = new \App\Permission();
                $perm->name         = $name;
                $perm->display_name = $op.' '.$cont;
                $perm->description  = '';
                $perm->save();
                if($role)$role->attachPermission($perm);
            }
        }
        $data['controllers'] = $controllers;
        $data['methods'] = $methods;
        return $data;
    }
}