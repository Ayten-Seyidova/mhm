<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RequestsRequest extends FormRequest
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
            'front_id_img' => 'required|file',
            'back_id_img' => 'required|file',
            'face_img' => 'required|file',
            'contact_data' => 'required|string',
            'paid_amount' => 'required|integer',
            'card_number' => 'required|integer',
            'package_id' => 'required|integer',
        ];
    }
    
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                ['message' => 'Access Forbidden'], 
                403
            )
        );
    }
}
