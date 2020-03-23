<?php

namespace App\Http\Requests\Goods;

use App\Http\Requests\StoreBase;
use App\Utils\Response;
use Illuminate\Foundation\Http\FormRequest;


class StoreGoodsPost extends StoreBase
{

    protected  $rules = [
            'title' => 'required|max:30',
            'price' => 'required|numeric|min:0.01',
            'line_price' => 'required|numeric|min:0.01',
            'count' => 'required|integer',
            'main_image' => 'required|max:255',
            'mImage' => 'required|array|max:9',
            'mImage.*.file_id' => 'required|integer',
            'mImage.*.url' => 'required', 
            'how' => [
                'integer',
                 'in:1,2'
            ],
            'file_id' =>  'required|integer',
            'content' => 'string|nullable',
            'code' => 'string|nullable',
            'cate_id' => 'required|integer',
           
            'spu' => [
                'array'
            ],
            'spu.*.k'=>'required|string',
            'spu.*.k_id'=>'required|integer',
            'spu.*.values' => 'required|array',
            'spu.*.values.*.v' => 'required|string',
            'spu.*.values.*.v_id' => 'required|integer',
            
            'sku' => 'required_with:spu|array',
            'sku.*.price' => 'required|numeric|min:0.01',
            'sku.*.count' => 'required|integer',
            'sku.*._id' => 'required|string',
            'sku.*.code' => 'required|nullable',
            'sku.*.lock' => 'required|nullable',
            'sku.*.sell' => 'required|nullable',
            'sku.*.weight' => 'required|integer',
            'sku.*.cast' => 'required|numeric|min:0.01',
    ];





   
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->rules;
        if($this->isMethod('post')){
            $rules['id'] = 'required|integer|min:1';
        }
        return $rules;
    }

    // public function messages()
    // {
    //     return [
    //         'title.required' => 'title不能为空'
    //     ];
    // }


  
}
