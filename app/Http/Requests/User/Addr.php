<?php


namespace App\Http\Requests\User;


use App\Http\Requests\StoreBase;

class Addr extends StoreBase
{


    public function rules(){

        return [
            'name' => ['required','string','min:2','max:30'],
            'phone' => ['required','string','max:30'],
            'addr_detail' => ['required','string','max:100'],
            'addr_full' => ['required','string','max:200'],
            'province_id' => ['required'],
            'city_id'=>['required'],
            'county_id'=>['required'],
            'town_id'=>['nullable'],
            'lat'=>'nullable|float',
            'lng'=>'nullable|float',
            'default'=>['integer']
        ];
    }


    public function message(){

        return [

        ];
    }

}
