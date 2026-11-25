<?php

namespace App\Jobs;

use App\Models\Automacao;
use App\Models\Inscrito;
use App\Services\EmailVariaveisService;
use App\Services\SendgridService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Str;

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

        // Troca {{nome}}, {{evento}}… pelos dados deste inscrito.
        $texto = $emailVariaveisService->preencher($mensagem->conteudo, $this->inscrito);

        // Versão com aparência: o texto dentro do layout (resources/views/emails/automacao.blade.php).
        $html = view('emails.automacao', [
            'conteudoHtml' => $this->converterTextoParaHtml($texto),
            // Prévia: trecho que aparece ao lado do assunto na caixa de entrada, antes de abrir o e-mail.
            // squish() junta o texto numa linha só; limit() corta em 120 caracteres e coloca "...".
            'previa' => Str::limit(Str::squish($texto), 120),
        ])->render();

        $sendgridService->enviarEmail(
            email: $contato->email,
            nome: $contato->nome,
            assunto: $emailVariaveisService->preencher($mensagem->assunto, $this->inscrito),
            texto: $texto,
            html: $html,
        );
    }

    /**
     * Converte o texto da mensagem para HTML, mantendo a aparência que a pessoa escreveu.
     *
     * Formatação aceita no texto:
     * - *negrito*        => <strong>negrito</strong>
     * - https://…        => link clicável
     * - linha em branco  => novo parágrafo
     */
    private function converterTextoParaHtml(string $texto): string
    {
        // e() protege o HTML: um "<" escrito no texto aparece como "<", não vira tag.
        $html = e($texto);

        // Links (http:// ou https://) viram clicáveis, ex.: o {{link_pagamento}}.
        $html = preg_replace('/https?:\/\/[^\s<]+/', '<a href="$0" style="color:#2563eb; text-decoration:underline;">$0</a>', $html);

        // *texto* vira negrito (igual ao WhatsApp). Precisa de letra colada nos asteriscos: "2 * 3 * 4" não muda.
        $html = preg_replace('/\*(\S(?:[^*\n]*\S)?)\*/', '<strong>$1</strong>', $html);

        // Cada bloco separado por uma linha em branco vira um parágrafo (<p>), com espaço entre eles.
        $paragrafos = preg_split('/\R{2,}/', trim($html));

        // Dentro do parágrafo, uma quebra de linha simples vira <br>.
        return collect($paragrafos)
            ->map(fn (string $paragrafo) => '<p style="margin:0 0 16px;">'.nl2br(trim($paragrafo)).'</p>')
            ->implode("\n");
    }
}
