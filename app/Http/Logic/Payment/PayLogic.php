<?php


namespace App\Http\Logic\Payment;


use Yansongda\LaravelPay\Facades\Pay;

class PayLogic
{


    protected $driver;

    public function __construct($payment)
    {

        $this->resolvePayment($payment);
    }

    protected function resolvePayment($payment){
        switch ($payment){
            case 1:
              $this->driver = Pay::alipay();
              break;

            case 2:
                $this->driver = Pay::wechat();
                break;
        }
    }


    public function pay($method,$params){
        return $this->driver->$method($params);
    }
}
