<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherModel extends Model
{
    protected $table="weather";
    protected $fillable = ["city_id", 'temperature', 'description','path_to_image'];

    public function city(){
        return $this->hasOne(CityModel::class, "id", "city_id");
    }

}
