<?php

namespace App\Http\Requests;

use App\Services\EmailVariaveisService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreEmailMensagemRequest extends FormRequest
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
            'assunto' => ['required', 'string', 'max:255'],
            'conteudo' => ['required', 'string', 'max:20000'],
        ];
    }

    /**
     * Recusa variáveis que o sistema não conhece, como {{nom}}.
     *
     * @return list<callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->hasAny(['assunto', 'conteudo'])) {
                    return;
                }

                $erro = app(EmailVariaveisService::class)
                    ->obterErroDasVariaveis($this->string('assunto')->toString(), $this->string('conteudo')->toString());

                if ($erro !== null) {
                    $validator->errors()->add('conteudo', $erro);
                }
            },
        ];
    }
}
