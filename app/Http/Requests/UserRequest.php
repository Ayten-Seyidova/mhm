<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'image' => 'image|mimes:jpeg,png,jpg|max:25000',
            "email" => "required|max:100|email|unique:users,email",
            "name" => "required|max:100",
            "about" => "max:190",
            "password" => "required|min:8|max:15",
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            "email.required" => "E-poçt adresi qeyd edilməlidir",
            "email.max" => "E-poçt ən çox 100 simvoldan ibarət olmalıdır",
            "email.email" => "E-poçt adresinin formatı düzgün deyil",
            "email.unique" => "Bu e-poçt adresinə aid hesab artıq mövcuddur",
            "name.required" => "Ad boş ola bilməz",
            "name.max" => "Ad ən çox 100 simvoldan ibarət olmalıdır",
            "about.max" => "Haqqında məlumat ən çox 100 simvoldan ibarət olmalıdır",
            "password.required" => "Şifrə qeyd edilməlidir",
            "password.min" => "Şifrə ən az 8 simvoldan ibarət olmalıdır",
            "password.max" => "Şifrə ən çox 15 simvoldan ibarət olmalıdır",
        ];
    }
}
