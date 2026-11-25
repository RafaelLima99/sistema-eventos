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
     * @param  string  $texto  conteúdo em texto simples (para programas de e-mail que não mostram HTML)
     * @param  string  $html  o mesmo conteúdo com aparência (layout com cores, cabeçalho e rodapé)
     */
    public function enviarEmail(string $email, string $nome, string $assunto, string $texto, string $html): void
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
                // Duas versões do mesmo conteúdo: simples e HTML. O programa de e-mail mostra a que conseguir.
                'content' => [
                    ['type' => 'text/plain', 'value' => $texto],
                    ['type' => 'text/html', 'value' => $html],
                ],
            ])
            ->throw(); // Se o SendGrid responder com erro, lança exceção.
    }
}
