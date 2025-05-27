<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'type' => 'required|in:deposit,withdraw,transfer',
            'amount' => 'required|numeric|min:0.01',
        ];

        if ($this->input('type') === 'deposit') {
            $rules['to_account_id'] = 'required|exists:accounts,id';
        } elseif ($this->input('type') === 'withdraw') {
            $rules['from_account_id'] = 'required|exists:accounts,id';
        } elseif ($this->input('type') === 'transfer') {
            $rules['from_account_id'] = 'required|exists:accounts,id|different:to_account_id';
            $rules['to_account_id'] = 'required|exists:accounts,id';
        }

        return $rules;
    }
}
