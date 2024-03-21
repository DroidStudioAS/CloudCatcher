<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityModel extends Model
{
    protected $table="cities";

    protected $fillable = ["country",'city_name'];

    public function forecast(){
        //one to many relationship: One city has many forecasts;
        return $this->hasMany(ForecastModel::class, "city_id","id");
    }
}
