<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\Expense|null $expense */
        $expense = $this->route('expense');

        return $expense && $this->user()?->can('update', $expense);
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'spent_at' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

}
