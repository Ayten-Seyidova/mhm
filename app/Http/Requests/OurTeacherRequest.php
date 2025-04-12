<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OurTeacherRequest extends FormRequest
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
            "name" => "required|max:190",
            "subject" => "max:190",
            "link" => "max:190",
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            "name.required" => "Ad və soyad qeyd edilməlidir",
            "name.max" => "Ad və soyad ən çox 190 simvoldan ibarət ola bilər",
            "subject.max" => "Fənn ən çox 190 simvoldan ibarət ola bilər",
            "link.max" => "Video linki ən çox 190 simvoldan ibarət ola bilər",
        ];
    }
}
