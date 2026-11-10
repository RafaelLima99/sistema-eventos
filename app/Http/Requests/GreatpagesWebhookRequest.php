<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GreatpagesWebhookRequest extends FormRequest
{
    /**
     * O token do webhook é conferido no controller.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Os nomes dos campos vêm dos rótulos do formulário da landing page na Greatpages.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Digite_Seu_Nome' => ['required', 'string', 'max:255'],
            'Digite_Seu_Melhor_E_mail' => ['required', 'email', 'max:255'],
            'Digite_Seu_WhatsApp_com_DDD' => ['nullable', 'string', 'max:20'],
            'Digite_Seu_CPF' => ['nullable', 'string'],
            'Qual_o_seu_cargo' => ['nullable', 'string', 'max:255'],
            'Area_de_atuacao_do_seu_negocio' => ['nullable', 'string', 'max:255'],
            'utm_source' => ['nullable', 'string', 'max:255'],
            'utm_medium' => ['nullable', 'string', 'max:255'],
            'utm_campaign' => ['nullable', 'string', 'max:255'],
            'utm_content' => ['nullable', 'string', 'max:255'],
        ];
    }
}
