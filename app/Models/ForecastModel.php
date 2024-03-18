<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForecastModel extends Model
{
    protected $table="forecast";

    protected $fillable=["city_id","temperature","date","path_to_image"];
}
