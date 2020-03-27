<?php

namespace App\Listeners\Admin\Login;

use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin{



    public function handle(Login $event){
        dd(1);
    }
}