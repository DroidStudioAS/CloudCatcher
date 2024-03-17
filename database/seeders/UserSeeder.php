<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //generate random username, password and email
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';

        $username = "user_";
        $password="";
        $email = "";

        $length = rand(6,16);
        $email.="@gmail.com";
        for($i=0; $i<500; $i++) {
            for ($j = 0; $j < $length; $j++) {
                $username .= $characters[rand(0, strlen($characters) - 1)];
                $password .= $characters[rand(0, strlen($characters) - 1)];
                $email .= $characters[rand(0, strlen($characters) - 1)];
            }

            $email.="@gmail.com";

            User::create([
                'name' => $username,
                "email" => $email,
                "password" => Hash::make($password)
            ]);
            $username="";
            $password="";
            $email="";
        }


    }
}
