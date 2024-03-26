<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCity extends Model
{
    protected $table = "user_favourites";

    protected $fillable = ["city_id","user_id"];

    public function cityModel(){
        return $this->hasOne(CityModel::class,"id","city_id");
    }
}
