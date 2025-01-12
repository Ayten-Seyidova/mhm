<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            "email" => "max:100",
            "class" => "max:100",
            "name" => "max:100",
        ];
    }

    public function messages()
    {
        return [
            "email.max" => "E-poçt ən çox 100 simvoldan ibarət olmalıdır",
            "class.max" => "Sinif ən çox 100 simvoldan ibarət olmalıdır",
            "name.max" => "Ad və soyad ən çox 100 simvoldan ibarət olmalıdır",
        ];
    }
}
