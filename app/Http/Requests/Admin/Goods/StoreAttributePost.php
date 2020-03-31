<?php

namespace App\Http\Requests\Admin\Goods;

use App\Http\Requests\StoreBase;

class StoreAttributePost extends StoreBase
{
 
    protected $rules = [

        'name' => 'required|string|max:10'

    ];


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }
}
