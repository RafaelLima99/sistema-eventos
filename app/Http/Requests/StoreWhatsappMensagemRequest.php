<?php

namespace App\Http\Requests;

use App\Models\WhatsappMensagem;
use App\Services\WhatsappTemplateService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWhatsappMensagemRequest extends FormRequest
{
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
            'titulo' => ['required', 'string', 'max:255'],
            // Só templates já aprovados pela Meta podem ser usados.
            'nome_template_meta' => ['required', Rule::in(array_keys(app(WhatsappTemplateService::class)->listarAprovados()))],
            'variaveis_template' => ['nullable', 'array'],
            'variaveis_template.*' => ['nullable', Rule::in(array_keys(WhatsappMensagem::CAMPOS_DO_SISTEMA))],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nome_template_meta.required' => 'Escolha um template.',
            'nome_template_meta.in' => 'Escolha um template aprovado.',
            'variaveis_template.*.in' => 'Escolha um campo do sistema válido.',
        ];
    }
}
