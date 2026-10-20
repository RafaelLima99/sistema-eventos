<?php

namespace App\Http\Requests;

use App\Enums\Uf;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Converte o valor do ingresso do formato brasileiro ("1.200,50") para decimal ("1200.50").
     * Valores sem vírgula ("600" ou "600.50") são mantidos como vieram.
     */
    protected function prepareForValidation(): void
    {
        if (str_contains((string) $this->input('preco_ingresso'), ',')) {
            $this->merge([
                'preco_ingresso' => str_replace(['.', ','], ['', '.'], $this->input('preco_ingresso')),
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
            'titulo' => ['required', 'string', 'max:255'],
            'data_evento' => ['required', 'date'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fim' => ['nullable', 'date_format:H:i', 'after:hora_inicio'],
            'local' => ['required', 'string', 'max:255'],
            'preco_ingresso' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'endereco' => ['nullable', 'string', 'max:255'],
            'cidade' => ['required', 'string', 'max:120'],
            'uf' => ['required', Rule::enum(Uf::class)],
            'link_inscricao' => ['nullable', 'url:http,https', 'max:2048'],
            'link_pagamento' => ['nullable', 'url:http,https', 'max:2048'],
        ];
    }
}
