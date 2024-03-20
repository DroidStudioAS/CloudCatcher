<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastModel extends Model
{
    protected $table="forecast";

    protected $fillable=["city_id","temperature","description","date","path_to_image","probability"];

    public function city(){
        //this defines many to one relationship, because many forecast entries are connected to
        //ONE city... from the perspective of the cities table we defined a one to many relation,
        //but we defined it from here because we are referencing the forecast model much more often in
        //our code.
        return $this->belongsTo(CityModel::class,"city_id","id");
    }
}
