<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            "exam_id" => "required",
            'image' => 'image|mimes:jpeg,png,jpg|max:25000',
            'image1' => 'image|mimes:jpeg,png,jpg|max:25000',
            'image2' => 'image|mimes:jpeg,png,jpg|max:25000',
            'image3' => 'image|mimes:jpeg,png,jpg|max:25000',
            'image4' => 'image|mimes:jpeg,png,jpg|max:25000',
            'image5' => 'image|mimes:jpeg,png,jpg|max:25000',
            "correct" => "required",
        ];
    }

    public function messages()
    {
        return [
            "exam_id.required" => "İmtahan seçilməlidir",
            'image.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            'image1.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image1.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image1.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            'image2.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image2.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image2.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            'image3.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image3.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image3.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            'image4.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image4.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image4.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            'image5.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image5.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image5.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            "correct.required" => "Düzgün cavab seçilməlidir",
        ];
    }
}
