<?php

namespace App\Http\Requests\Admin\Goods;

use App\Http\Requests\StoreBase;
use Illuminate\Foundation\Http\FormRequest;

class ModifyStatus extends StoreBase
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids' => 'required|array|max:10',
            'ids.*'=> 'integer|min:1'
        ];
    }
}
