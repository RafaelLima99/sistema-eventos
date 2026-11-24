<?php

namespace App\Jobs;

use App\Models\Automacao;
use App\Models\Inscrito;
use App\Services\EmailVariaveisService;
use App\Services\SendgridService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Envia o e-mail de uma automação para um inscrito.
 *
 * Roda na fila (container "queue"), fora do webhook: a Hotmart e a Greatpages recebem a resposta na hora,
 * sem esperar o SendGrid. Se o envio falhar, a fila tenta de novo.
 */
class EnviarEmailDaAutomacao implements ShouldQueue
{
    use Queueable;

    /**
     * Quantas vezes tenta enviar antes de desistir (o job vai para a tabela failed_jobs).
     */
    public int $tries = 3;

    /**
     * Espera entre as tentativas, em segundos: 1 minuto e depois 5 minutos.
     *
     * @var list<int>
     */
    public array $backoff = [60, 300];

    public function __construct(
        public Automacao $automacao,
        public Inscrito $inscrito,
    ) {}

    public function handle(SendgridService $sendgridService, EmailVariaveisService $emailVariaveisService): void
    {
        $mensagem = $this->automacao->emailMensagem;
        $contato = $this->inscrito->contato;

        $sendgridService->enviarEmail(
            email: $contato->email,
            nome: $contato->nome,
            // Troca {{nome}}, {{evento}}… pelos dados deste inscrito.
            assunto: $emailVariaveisService->preencher($mensagem->assunto, $this->inscrito),
            texto: $emailVariaveisService->preencher($mensagem->conteudo, $this->inscrito),
        );
    }
}
