<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterWeatherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("weather", function (Blueprint $table){
            $table->dropColumn("city");

           $table->unsignedBigInteger("city_id")->after("id")->default(rand(1,100));
           $table->foreign("city_id")->references("id")->on("cities")->onDelete("restrict");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
