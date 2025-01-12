<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'instagram' => 'max:190',
            'facebook' => 'max:190',
            'customer_service' => 'max:190',
        ];
    }

    public function messages()
    {
        return [
            'instagram.max' => 'Instagram ən çox 190 simvoldan ibarət ola bilər',
            'facebook.max' => 'Facebook ən çox 190 simvoldan ibarət ola bilər',
            'customer_service.max' => 'Müştəri xismətləri ən çox 190 simvoldan ibarət ola bilər',
        ];
    }
}
