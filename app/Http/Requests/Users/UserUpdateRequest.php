<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email'                 => ['nullable', 'email'],
            'old_password'          => ['required', 'current_password'],
            'password'              => ['nullable', 'string', 'min:6', 'same:password_confirmation'],
            'password_confirmation' => ['nullable', 'string', 'min:6', 'same:password'],
            'image'                 => ['nullable', 'mimes:jpg,png,jpeg,avif,webp'],
        ];
    }
}
