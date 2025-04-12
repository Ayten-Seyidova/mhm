<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
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
            "title" => "required",
            "content" => "required",
            "video" => "required|max:190",
            "sub_direction_id" => "required",
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.mimes' => 'Şəkil formatı düzgün deyil (qəbul olunan formatlar: jpg, jpeg, png)',
            'image.max' => 'Şəkil ölçüsü ən çox 25 Mb ola bilər',
            "title.required" => "Başlıq qeyd edilməlidir",
            "content.required" => "Məzmun qeyd edilməlidir",
            "video.required" => "Video linki qeyd edilməlidir",
            "video.max" => "Video linki ən çox 190 simvoldan ibarət ola bilər",
            "sub_direction_id.required" => "İstiqamət seçilməlidir",
        ];
    }
}
