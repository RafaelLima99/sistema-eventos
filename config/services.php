<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'hotmart' => [
        // Token enviado pela Hotmart no header X-HOTMART-HOTTOK (painel da Hotmart > Ferramentas > Webhook).
        'hottok' => env('HOTMART_HOTTOK'),
    ],

    'greatpages' => [
        // Token enviado pela Greatpages no header Authorization (campo "Token" na configuração do webhook).
        'token' => env('GREATPAGES_TOKEN'),
    ],

    'whatsapp' => [
        // API oficial do WhatsApp (Cloud API), usada para listar os templates aprovados.
        // Token e ID da conta ficam no Meta for Developers > seu app > WhatsApp > Configuração da API.
        'token' => env('WHATSAPP_TOKEN'),
        'business_account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID'),
        'versao' => env('WHATSAPP_API_VERSION', 'v24.0'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
