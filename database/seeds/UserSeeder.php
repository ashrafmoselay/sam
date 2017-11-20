<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $list = [
            'name'=>"Admin",
            'email'=>"admin@admin.com",
            'password'=>bcrypt(123456),
            'role'=>1,
            'remember_token'=>str_random(10)
        ];
        DB::table('users')->insert($list);

        $list = [ 
            [
            'name'=>"admin",
            'display_name'=>"مسئول",
            ], 
            [
            'name'=>"user",
            'display_name'=>"مستخدم",
            ],
        ];
        DB::table('roles')->insert($list);
        DB::table('role_user')->insert(['user_id'=>1,'role_id'=>1]);
        \App\Permission::getRoutePermission(true);
    }
}
