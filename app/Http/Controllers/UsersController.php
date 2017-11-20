<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Validation\Rule;

class UsersController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::paginate(25);
        return view('users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User;
        return view('users.create',compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);
        $user->roles()->attach($request->role);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user= User::find($id);
        return view('users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user= User::find($id);
        //dd($request->all());
        $rules = [
                'name' => 'required|max:255',
                'email' => [
                    'required',
                    Rule::unique('users')->ignore($user->id),
                ],
                'password' => 'required|min:4|confirmed',
            ];
        $customMessages = [
            'required' => 'هذا الحقل مطلوب',
            'min' =>"لا يجب أن تكون كلمة المرور أقل من 4",
            'confirmed' =>"من فضلك أكد كلمة المرور",

        ];
        $this->validate($request, $rules, $customMessages);
        //\DB::table('role_user')->where('user_id', $id)->delete();

        $inputs = $request->all();
        $inputs['password'] = bcrypt($request->password);
        $user->update($inputs);
        try{
            \DB::table('role_user')
            ->where('user_id', $id)
            ->update(['role_id' => $inputs['role']]);
            //$user->roles()->attach($request->role);
        }catch(Exception $e){
           
        }
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         User::destroy($id);
        return redirect()->route('users.index');
    }
}
