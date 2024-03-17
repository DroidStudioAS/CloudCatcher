<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for($i=0; $i<500; $i++) {

            $username="user_" . $faker->userName;
            $password =$faker->password;
            $email=$faker->safeEmail;

            User::create([
                'name' => $username,
                "email" => $email,
                "password" => Hash::make($password)
            ]);
        }


    }
}
