<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContatoImportacaoRequest extends FormRequest
{
    /**
     * Separa os erros da importação dos erros do formulário de novo contato.
     *
     * @var string
     */
    protected $errorBag = 'importacao';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'arquivo' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ];
    }
}
