<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTreasuryMovementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treasury_movement', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->boolean('type')->default(0)->comment = "1 => withdraw , 2 => deposite";
            $table->boolean('user_type')->default(0)->comment = "1 => for client ,2=>suppliers ,3 => for partner,4=>other";
            $table->integer("client_id")->nullable();
            $table->integer("supplier_id")->nullable();
            $table->integer("partner_id")->nullable();
            $table->double('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treasury_movement');
    }
}
