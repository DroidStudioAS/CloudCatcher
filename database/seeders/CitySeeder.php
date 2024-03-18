<?php

namespace Database\Seeders;

use App\Models\CityModel;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $amount = $this->command->getOutput()->ask("How many cities should we generate?",5);

        $this->command->getOutput()->progressStart($amount);
        for($i=0; $i<$amount; $i++){
            CityModel::create([
               "city_name"=>$faker->city
            ]);
            $this->command->getOutput()->progressAdvance(1);
        }

        $this->command->getOutput()->progressFinish();

    }
}
