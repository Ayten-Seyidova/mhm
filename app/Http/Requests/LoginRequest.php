<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            "email" => "required|min:3|max:100|email",
            "password" => "required|min:8|max:15",
        ];
    }

    public function messages()
    {
        return [
            "email.required" => "E-poçt ünvanınızı daxil edin",
            "email.min" => "E-poçt ən azı 3 simvoldan ibarət olmalıdır",
            "email.max" => "E-poçt ən çox 100 simvoldan ibarət olmalıdır",
            "email.email" => "E_poçt formatı düzgün deyil",
            "password.required" => "Şifrənizi daxil edin",
            "password.min" => "Şifrə ən azı 8 simvoldan ibarət olmalıdır",
            "password.max" => "Şifrə ən çox 15 simvoldan ibarət olmalıdır"
        ];
    }
}
