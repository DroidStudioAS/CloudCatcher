<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForecastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create("forecast",function (Blueprint $table){
            $table->id();

            $table->unsignedBigInteger("city_id");
            $table->foreign("city_id")->references("id")->on("cities")->onDelete("restrict");

            $table->float("temperature");
            $table->date("date");
            $table->string("path_to_image");

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
        //
    }
}
