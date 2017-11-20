<?php
namespace App\Http\Controllers;
use Auth;
use Illuminate\Support\Str;

class BaseController extends Controller
{
	protected $user;
	public function __construct()
	{
		$this->middleware('auth');
	    $this->middleware(function ($request, $next) {
	        $this->user= Auth::user();
			$this->middleware('auth');
		 	if($this->user){
			 	$routeArray = app('request')->route()->getAction();
				$controllerAction = class_basename($routeArray['controller']);
				list($controller, $action) = explode('@', $controllerAction);
				//$controller = Str::lower($controller);
				$action = "$controller-$action";
				$perm = \Ntrust::can($action);
				//dd($perm);
				if(!$perm) abort(503, 'Unauthorized action.');
			}
	        return $next($request);
	    });
	}
}
