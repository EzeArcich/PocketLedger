<?php

namespace App\Http\Requests;

use App\Models\Expense;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Lógica ed Illuminate\Foundation\Auth\Access\Authorizable - a través de can(), llama a policy
        return $this->user()?->can('create',Expense::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'spent_at' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}
