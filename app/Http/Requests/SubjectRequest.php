<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectRequest extends FormRequest
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
            "name" => "required|max:190",
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "Mövzu adı boş ola bilməz",
            "name.max" => "Mövzu adı ən çox 190 simvoldan ibarət olmalıdır",
        ];
    }
}
