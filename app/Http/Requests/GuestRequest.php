<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestRequest extends FormRequest
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
            "name" => "required|max:100",
            "phone" => "required|max:100",
            "direction_id" => "required",
            "sub_direction_id" => "required",
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            "name.required" => "Ad və soyad qeyd edilməlidir",
            "name.max" => "Ad və soyad ən çox 100 simvoldan ibarət olmalıdır",
            "phone.required" => "Telefon qeyd edilməlidir",
            "phone.max" => "Telefon ən çox 100 simvoldan ibarət olmalıdır",
            "direction_id.required" => "Hazırlıq istiqaməti seçilməlidir",
            "sub_direction_id.required" => "İstiqamət seçilməlidir",
        ];
    }
}
