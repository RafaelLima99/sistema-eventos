<?php

namespace App\Http\Requests;

use App\Enums\CanalMensagem;
use App\Enums\FiltroPublico;
use App\Enums\StatusAutomacao;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAutomacaoRequest extends FormRequest
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
            'evento_id' => ['required', 'exists:eventos,id'],
            'horas' => ['required', 'integer', 'min:0'],
            'minutos' => ['required', 'integer', 'between:0,59'],
            'filtro' => ['required', Rule::enum(FiltroPublico::class)],
            'canal' => ['required', Rule::enum(CanalMensagem::class)],
            'whatsapp_mensagem_id' => ['nullable', 'required_if:canal,whatsapp', 'exists:whatsapp_mensagens,id'],
            'email_mensagem_id' => ['nullable', 'required_if:canal,email', 'exists:email_mensagens,id'],
            'status' => ['required', Rule::enum(StatusAutomacao::class)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'whatsapp_mensagem_id.required_if' => 'Escolha a mensagem de WhatsApp.',
            'email_mensagem_id.required_if' => 'Escolha a mensagem de e-mail.',
        ];
    }
}
