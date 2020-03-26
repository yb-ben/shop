<?php

namespace App\Http\Requests\Goods;

use App\Http\Requests\StoreBase;
use App\Utils\Response;
use Illuminate\Foundation\Http\FormRequest;


class StoreGoodsPost extends StoreBase
{

    protected  $rules = [
            'id' => 'integer|min:1',
            'title' => 'required|max:30',
            'price' => 'required|numeric|min:0.01',
            'line_price' => 'required|numeric|min:0.01',
            'count' => 'required|integer',
            'main_image' => 'required|max:255',
            'mImage' => 'nullable|array|max:9',
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
            'status' => 'required|in:0,1',

            'is_timing' => 'required|in:0,1',
            'up_at' =>'required_if:is_timing,1|integer',
            'limit' => 'nullable|array',
            'limit.0.type' =>'in:1,2',
            'limit.0.count' => 'required_if:limit.0.type,1|integer',
            'limit.0.circle' => 'required_if:limit.0.type,2|in:1,2,3',
            'limit.0.circle_count' =>'required_if:limit.0.type,2|integer',
            
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
      
        return $rules;
    }

    // public function messages()
    // {
    //     return [
    //         'title.required' => 'title不能为空'
    //     ];
    // }


  
}
