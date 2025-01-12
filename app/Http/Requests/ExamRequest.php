<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamRequest extends FormRequest
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
            "duration" => "required",
            "subject" => "required|max:190",
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            "name.required" => "İmtahan adı boş ola bilməz",
            "name.max" => "İmtahan adı ən çox 190 simvoldan ibarət olmalıdır",
            "duration.required" => "Müddət boş ola bilməz",
            "subject.required" => "Mövzu boş ola bilməz",
            "subject.max" => "Mövzu ən çox 190 simvoldan ibarət olmalıdır",
        ];
    }
}
