<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            "email" => "required|max:100|email",
            "name" => "required|max:100",
        ];
    }

    public function messages()
    {
        return [
            "email.required" => "E-poçt adresi qeyd edilməlidir",
            "email.max" => "E-poçt ən çox 100 simvoldan ibarət olmalıdır",
            "email.email" => "E-poçt adresinin formatı düzgün deyil",
            "name.required" => "Ad boş ola bilməz",
            "name.max" => "Ad ən çox 100 simvoldan ibarət olmalıdır",
        ];
    }
}
