<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use File;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingController extends BaseController
{
    //
	
	public function index()
	{
		 $setting = config('custom-setting');
		 return view('setting.edit',['setting'=>$setting]);
	
	}
	
	public function update(Request $request)
	{
		 $inputs = $request->except('_token');
		 $data = var_export($inputs, 1);
		 //dd($data);
         $title = "// generated at ".date("Y-m-d H:i:s");
		 $contents = File::put(config_path().'/custom-setting.php',"<?php\n $title \nreturn $data;");
		 $request->session()->flash('alert-success', 'تم حفظ الأعدادات بنجاح');
		 sleep(3);
		 return back()->withInput();
	}
}
