<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'oldPassword' => ['required', 'string'],
            'newPassword' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Za-z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
                'different:oldPassword',
            ],
            'confirmPassword' => ['required', 'same:newPassword'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'newPassword.min' => 'The new password must be at least 8 characters.',
            'newPassword.regex' => 'The new password must contain a letter, a number, and a symbol.',
            'newPassword.different' => 'The new password must be different from your old password.',
            'confirmPassword.same' => 'The password confirmation does not match.',
        ];
    }
}
