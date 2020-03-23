<?php

namespace App\Http\Requests\Goods;

use App\Utils\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreGoodsPost extends FormRequest
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
                'required',
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
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = $this->rules;
        if($this->isPost()){
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


    public function failedValidation($validator)
    {
        $error = $validator->errors()->all();
        throw new HttpResponseException(Response::apiError($error[0]));
    }
}
