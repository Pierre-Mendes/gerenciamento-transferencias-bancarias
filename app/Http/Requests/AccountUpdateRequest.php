<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|required|exists:users,id',
            'number' => 'sometimes|required|string|unique:accounts,number,' . $this->route('account'),
            'balance' => 'sometimes|required|numeric',
        ];
    }
}
