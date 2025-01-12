<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            "title" => "required|max:500",
            "content" => "required",
        ];
    }

    public function messages()
    {
        return [
            "title.required" => "Başlıq boş ola bilməz",
            "title.max" => "Başlıq ən çox 500 simvoldan ibarət olmalıdır",
            "content.required" => "Məzmun boş ola bilməz",
        ];
    }
}
