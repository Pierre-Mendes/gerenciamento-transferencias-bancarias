<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'cpf' => 'sometimes|required|string|max:14|unique:users,cpf,' . $this->route('user'),
            'email' => 'sometimes|required|email|unique:users,email,' . $this->route('user'),
            'password' => 'nullable|string|min:6',
        ];
    }
}
