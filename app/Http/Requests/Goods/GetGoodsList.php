<?php

namespace App\Http\Requests\Goods;

use App\Http\Requests\StoreBase;
use App\Utils\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetGoodsList extends StoreBase
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'limit' => 'integer|min:1|max:20',
            'page' => 'integer|min:1',
            'status' => [
                Rule::in([-1,0,1,2])
            ]
        ];
    }


    public function failedValidation($validator)
    {
        return Response::api();
    }
}
