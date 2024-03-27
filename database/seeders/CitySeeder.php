<?php

namespace Database\Seeders;

use App\Models\CityModel;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function  run()
    {
        //all cities and countries in europe




        $this->command->getOutput()->progressStart();
        foreach (CityModel::european_countries as $country=>$cities){
            $this->command->getOutput()->write($country . " : ");
            foreach ($cities as $city){
                $this->command->getOutput()->write($city . " : ");
                CityModel::create([
                    "country"=>$country,
                    "city_name"=>$city
                ]);
                $this->command->getOutput()->progressAdvance(1);
            }
        }
        $this->command->getOutput()->progressFinish();


    }

}
