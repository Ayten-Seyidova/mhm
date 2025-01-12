<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;

class OtpRequest extends FormRequest
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
        $request = [
            'phoneNumber' => 'required|max:15',
        ];

        if ($this->method()=="POST") {
            $request['phoneNumber'] = 'required|max:15|unique:customers,phone';
        }

        return $request;
    }

     public function getValidatorInstance()
    {
        $this->cleanPhoneNumber();
        return parent::getValidatorInstance();
    }

    protected function cleanPhoneNumber()
    {
        if($this->request->has('phoneNumber')){
            $this->merge([
                'phoneNumber' => str_replace(['+','_',''], '', $this->request->get('phoneNumber'))
            ]);
        }
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
