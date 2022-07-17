<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users,email',

            // https://stackoverflow.com/questions/31539727/laravel-password-validation-rule
            'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
        ];
    }

    public function messages()
    {
        $passwordWeakMessage = "Your password is too weak. It should be at least 6 character with combination of UPPERCASE, lowercase, digit, and symbol.";

        return [
            'password.regex' => $passwordWeakMessage,
        ];
    }
}
