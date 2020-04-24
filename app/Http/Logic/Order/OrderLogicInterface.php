<?php


namespace App\Http\Logic\Order;


interface OrderLogicInterface
{


    public function getFreight();
    public function getUser();
    public function getDiscountPrice();
    public function getTotalPrice();

    public function createOrder($order_id);
}
