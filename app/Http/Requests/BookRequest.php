<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            "title" => "required|max:190",
            "teacher_name" => "required|max:190",
            "price" => "required",
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            "title.required" => "Başlıq qeyd edilməlidir",
            "title.max" => "Başlıq ən çox 190 simvoldan ibarət ola bilər",
            "teacher_name.required" => "Müəllimin ad və soyadı qeyd edilməlidir",
            "teacher_name.max" => "Müəllimin ad və soyadı ən çox 190 simvoldan ibarət ola bilər",
            "price.required" => "Qiymət qeyd edilməlidir",
        ];
    }
}
