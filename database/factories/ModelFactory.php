<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;


$factory->define(App\Model\Admin::class,function(Faker $faker){
    static $password;
    return [
        'username' => $faker->name,
        'password' => $password?:$password = bcrypt('123456'),
        'email' => $faker->email,
        'remember_token' => Str::random(10)
    ];
});
