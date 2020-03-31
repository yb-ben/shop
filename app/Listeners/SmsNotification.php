<?php


namespace App\Listeners;

use Huyibin\Sms\Events\Events\VerificationCodeSend;

class SmsNotification{



    public function handle(VerificationCodeSend $event){
        ob_start();
        print_r($event);
    }
}