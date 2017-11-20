<?php

use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [];
        for ($i=0; $i < 50; $i++) { 
        	$n = $i+1;
            $cost = rand(100,900);
        	$list [] =  [
            			'title'=>"منتج رقم $n",
                        'category_id'=>0,
            			'description'=>"Product description # $n",
		            	'cost'=>$cost,
            			'price'=>rand(1000,2000),
            			//'quantity'=>rand(1,100),
                        //'sale_count'=>0,
                        'last_avg_cost'=>$cost,
                        'code'=>str_random(6)
            			];
        }
    	DB::table('products')->insert($list);
    }
}
