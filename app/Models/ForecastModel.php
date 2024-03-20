<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForecastModel extends Model
{
    protected $table="forecast";

    protected $fillable=["city_id","temperature","description","date","path_to_image"];

    public function city(){
        return $this->belongsTo(CityModel::class,"city_id","id");
    }
}
