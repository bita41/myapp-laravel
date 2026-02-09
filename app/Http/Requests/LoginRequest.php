<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $email = $this->input('user.email');
        if (is_string($email)) {
            $this->merge([
                'user' => array_merge($this->input('user', []), ['email' => strtolower(trim($email))]),
            ]);
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'user.email' => ['required', 'string', 'email:rfc,dns', 'max:255'],
            'user.password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user.email.required' => __('Fill in the required fields.'),
            'user.email.email' => __('The email must be a valid email address.'),
            'user.email.max' => __('The email may not be greater than :max characters.'),
            'user.password.required' => __('Fill in the required fields.'),
            'user.password.min' => __('The password must be at least :min characters.'),
            'user.password.max' => __('The password may not be greater than :max characters.'),
        ];
    }
}
