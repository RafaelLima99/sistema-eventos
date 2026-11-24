<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Envia e-mails pela API do SendGrid (Twilio).
 *
 * Credencial no .env: SENDGRID_API_KEY (ver config/services.php).
 * Remetente: MAIL_FROM_ADDRESS e MAIL_FROM_NAME, que precisam estar verificados no SendGrid.
 */
class SendgridService
{
    /**
     * Envia um e-mail para uma pessoa.
     * Se o SendGrid responder com erro, lança exceção (o job que chamou tenta de novo).
     *
     * @param  string  $texto  conteúdo em texto simples (as quebras de linha viram <br> na versão HTML)
     */
    public function enviarEmail(string $email, string $nome, string $assunto, string $texto): void
    {
        Http::withToken(config('services.sendgrid.api_key')) // Envia a chave da API: prova que o sistema pode enviar pela conta.
            ->connectTimeout(3)
            ->timeout(10)
            ->post('https://api.sendgrid.com/v3/mail/send', [
                'personalizations' => [
                    ['to' => [['email' => $email, 'name' => $nome]]],
                ],
                'from' => [
                    'email' => config('mail.from.address'),
                    'name' => config('mail.from.name'),
                ],
                'subject' => $assunto,
                // Duas versões do mesmo texto: simples e HTML. O programa de e-mail mostra a que conseguir.
                // e() protege o HTML (um "<" no texto não vira tag) e nl2br() transforma as quebras de linha em <br>.
                'content' => [
                    ['type' => 'text/plain', 'value' => $texto],
                    ['type' => 'text/html', 'value' => nl2br(e($texto))],
                ],
            ])
            ->throw(); // Se o SendGrid responder com erro, lança exceção.
    }
}
