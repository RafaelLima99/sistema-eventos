<?php

namespace App\Http\Requests;

use App\Enums\FiltroPublico;
use App\Enums\StatusDisparo;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDisparoRequest extends FormRequest
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
            'whatsapp_mensagem_id' => ['required', 'exists:whatsapp_mensagens,id'],
            'todos_contatos' => ['required', 'boolean'],
            // Com "Inscritos em evento", é preciso marcar pelo menos um evento.
            'eventos' => ['exclude_if:todos_contatos,1', 'required', 'array'],
            'eventos.*' => ['exists:eventos,id'],
            'filtro' => ['required', Rule::enum(FiltroPublico::class)],
            // Data e hora andam juntas: preenchida uma, a outra é obrigatória. Em branco as duas, o envio é imediato.
            'data' => ['nullable', 'required_with:hora', 'date_format:Y-m-d'],
            'hora' => ['nullable', 'required_with:data', 'date_format:H:i'],
            // O botão clicado decide: "Salvar rascunho" ou "Agendar envio". "Concluído" só o envio marca.
            'status' => ['required', Rule::in([StatusDisparo::Rascunho->value, StatusDisparo::Agendado->value])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'whatsapp_mensagem_id.required' => 'Escolha a mensagem de WhatsApp.',
            'eventos.required' => 'Marque pelo menos um evento.',
            'data.required_with' => 'Informe a data do envio.',
            'hora.required_with' => 'Informe a hora do envio.',
        ];
    }
}
