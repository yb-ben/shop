<?php

namespace App\Http\Requests\Goods;

use App\Http\Requests\StoreBase;
use App\Model\GoodsAttr;
use Illuminate\Foundation\Http\FormRequest;

class StoreValuesPost extends StoreBase
{

    protected $rules = [

        'val' => 'required|string|max:30',
        '_id' => [
            'required',
            'integer',
        ]
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->ruels;
        
        $rules['_id'][] =  function ($attribute, $value, $fail) {
            GoodsAttr::where('id',$value)->count() || $fail($attribute);
        };

        return $rules;
    }
}
