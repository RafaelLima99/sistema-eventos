<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContatoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Tira a máscara do CPF: "312.048.271-11" => "31204827111".
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('cpf')) {
            $this->merge([
                'cpf' => preg_replace('/\D/', '', $this->input('cpf')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'cpf' => ['nullable', 'digits:11', Rule::unique('contatos')],
            'cargo' => ['nullable', 'string', 'max:255'],
            'negocio' => ['nullable', 'string', 'max:255'],
        ];
    }
}
