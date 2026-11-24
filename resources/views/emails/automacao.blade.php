{{--
    Layout dos e-mails das automações: só dá a aparência. O conteúdo, inclusive as informações
    do evento, é o texto que o usuário escreve na mensagem.

    Estilo de e-mail transacional: fundo cinza, área branca com o texto e rodapé discreto.

    E-mail não é página web: Gmail, Outlook e celular ignoram boa parte do CSS.
    Por isso o layout usa <table> e estilos dentro de cada tag (style="..."), que é o que esses programas aceitam.

    Variáveis:
    - $conteudoHtml: texto da mensagem, já com as variáveis preenchidas e convertido para HTML;
    - $previa: frase curta que o Gmail/celular mostram ao lado do assunto, antes de abrir o e-mail.
--}}
@php
    // Fonte do próprio sistema de cada aparelho (iPhone, Android, Windows, Mac), com Arial de reserva.
    $fonte = "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    {{-- Pede para o modo escuro do celular não inverter as cores do e-mail. --}}
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>{{ $previa }}</title>
</head>
<body style="margin:0; padding:0; width:100%; background-color:#f4f6fb; -webkit-text-size-adjust:100%;">

    {{-- Prévia: texto escondido que aparece na lista de e-mails, ao lado do assunto. --}}
    <div style="display:none; max-height:0; overflow:hidden; opacity:0; mso-hide:all;">{{ $previa }}</div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6fb;">
        <tr>
            <td align="center" style="padding:40px 16px;">

                {{-- O Outlook do Windows ignora max-width: este bloco (só ele lê) fixa a largura em 600px. --}}
                <!--[if mso]><table role="presentation" width="600" cellpadding="0" cellspacing="0"><tr><td><![endif]-->

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;">

                    {{-- Área branca com o texto. --}}
                    <tr>
                        <td style="background-color:#ffffff; border:1px solid #e5e9f0; border-radius:12px; padding:40px 40px 24px; font-family:{{ $fonte }}; font-size:16px; line-height:1.7; color:#334155;">
                            {!! $conteudoHtml !!}
                        </td>
                    </tr>

                    {{-- Rodapé: por que a pessoa recebeu e quem enviou (MAIL_FROM_NAME, o ano muda sozinho). --}}
                    <tr>
                        <td align="center" style="padding:28px 24px 0; font-family:{{ $fonte }}; font-size:12px; line-height:1.7; color:#94a3b8;">
                            Você está recebendo este e-mail porque se inscreveu em um dos nossos eventos.<br>
                            © {{ now()->year }} {{ config('mail.from.name') }}
                        </td>
                    </tr>

                </table>

                <!--[if mso]></td></tr></table><![endif]-->

            </td>
        </tr>
    </table>
</body>
</html>
