<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreGoodsPost extends FormRequest
{
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
        return [
            'title' => 'required|max:30',
            'main_image' => 'required',
            'mImage' => 'required|array|max:9',
            'price' => 'required|numeric|min:0.01',
            'line_price' => 'required|numeric|min:0.01',
            'cate_id' => 'required',
            'attrValues' =>[
                'array',
                 function($attr,$value, $fail){
                     if(!empty($value)){
                        foreach($value as $v){
                            if(!(isset($v['id']) && isset($v['values']) && !empty($v['values']))){
                                $fail($attr,' 参数错误');
                                //return false;
                            }
                        }
                     }
                },
            ],
            'sku' => [
                'required_with:attrValues',
                'array',
                function($attr,$value,$fail){
                    $attrValues  = $this->post('attrValues');
                    $ids = array_column($attrValues,'id');
                    foreach($value as $v){
                       if( count($v) !==(count($ids) +3)){
                           $fail($attr,' 参数错误');
                            //return false;
                        }
                    }
                }
            ],
            'sku.*.price' => 'required|numeric|min:0.01',
            'sku.*.line_price' => 'numeric|min:0.01',
            'sku.*.count' => 'required|integer',
            
        ];
    }

    // public function messages()
    // {
    //     return [];
    // }
}
