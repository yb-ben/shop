<?php

namespace App\Http\Requests;

use App\Utils\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBase extends FormRequest
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

    public function failedValidation($validator)
    {
        $error = $validator->errors()->all();
        throw new HttpResponseException(Response::apiError($error[0]));
    }
}
