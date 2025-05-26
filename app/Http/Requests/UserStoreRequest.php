<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:users,cpf',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];
    }
}
