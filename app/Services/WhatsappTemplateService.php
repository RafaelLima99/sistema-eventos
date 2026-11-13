<?php

namespace App\Services;

use App\Enums\StatusTemplateMeta;

/**
 * Templates de WhatsApp cadastrados na Meta (WhatsApp Manager).
 *
 * Ainda não há integração com a Meta: os templates abaixo são fictícios.
 * Quando a integração existir, só este service muda (listar e buscar passam a consultar a API da Meta),
 * e o resto do sistema continua chamando os mesmos métodos.
 */
class WhatsappTemplateService
{
    /**
     * Todos os templates, na ordem em que aparecem na tela, indexados pelo nome do template.
     *
     * Cada template tem: nome, categoria, categoria_classe, idioma, status (StatusTemplateMeta), qualidade,
     * id, corpo (texto com {{1}}, {{2}}…) e total_variaveis.
     *
     * @return array<string, array<string, mixed>>
     */
    public function listar(): array
    {
        $templates = [];

        foreach ($this->templatesFicticios() as $template) {
            $templates[$template['nome']] = [
                ...$template,
                'total_variaveis' => $this->contarVariaveis($template['corpo']),
            ];
        }

        return $templates;
    }

    /**
     * @return array<string, mixed>|null o template, ou null se não existir
     */
    public function buscar(?string $nome): ?array
    {
        return $this->listar()[$nome] ?? null;
    }

    /**
     * Templates que a Meta já aprovou, indexados pelo nome. Só estes podem ser escolhidos numa mensagem.
     * Na integração, vira a consulta à API da Meta filtrando por status=APPROVED.
     *
     * @return array<string, array<string, mixed>>
     */
    public function listarAprovados(): array
    {
        return array_filter($this->listar(), fn (array $template) => $template['status'] === StatusTemplateMeta::Aprovado);
    }

    /**
     * Quantidade de variáveis do template: o maior número usado no texto ("Olá {{1}}, {{2}}" => 2).
     */
    private function contarVariaveis(string $corpo): int
    {
        preg_match_all('/\{\{(\d+)\}\}/', $corpo, $encontradas);

        return $encontradas[1] === [] ? 0 : max(array_map('intval', $encontradas[1]));
    }

    /**
     * Dados fictícios. Substituir pela resposta da API da Meta quando houver integração.
     *
     * @return list<array<string, mixed>>
     */
    private function templatesFicticios(): array
    {
        return [
            [
                'nome' => 'confirmacao_inscricao',
                'categoria' => 'Utilidade',
                'categoria_classe' => 'util',
                'idioma' => 'pt_BR',
                'status' => StatusTemplateMeta::Aprovado,
                'qualidade' => 'Alta',
                'id' => '1180239776450912',
                'corpo' => "Olá {{1}}! 🎉\n\nSua inscrição no *{{2}}* foi confirmada.\n\n📅 {{3}} às {{4}}\n📍 {{5}}\n\nGuarde este contato. Enviaremos os próximos avisos por aqui.",
            ],
            [
                'nome' => 'lembrete_pagamento_15min',
                'categoria' => 'Utilidade',
                'categoria_classe' => 'util',
                'idioma' => 'pt_BR',
                'status' => StatusTemplateMeta::Aprovado,
                'qualidade' => 'Alta',
                'id' => '1180239776450913',
                'corpo' => 'Olá {{1}}! Sua vaga no {{2}} está reservada, mas o pagamento não foi concluído. Finalize: {{3}}',
            ],
            [
                'nome' => 'lembrete_vespera',
                'categoria' => 'Utilidade',
                'categoria_classe' => 'util',
                'idioma' => 'pt_BR',
                'status' => StatusTemplateMeta::Aprovado,
                'qualidade' => 'Média',
                'id' => '1180239776450914',
                'corpo' => 'Amanhã é o dia, {{1}}! {{2}} — {{3}} às {{4}}. Chegue 30 min antes.',
            ],
            [
                'nome' => 'aviso_dia_evento',
                'categoria' => 'Utilidade',
                'categoria_classe' => 'util',
                'idioma' => 'pt_BR',
                'status' => StatusTemplateMeta::Aprovado,
                'qualidade' => 'Alta',
                'id' => '1180239776450915',
                'corpo' => 'Bom dia, {{1}}! Hoje tem {{2}}. Início às {{3}}. Seu QR Code no link.',
            ],
            [
                'nome' => 'convite_lista_quente',
                'categoria' => 'Marketing',
                'categoria_classe' => 'mkt',
                'idioma' => 'pt_BR',
                'status' => StatusTemplateMeta::EmAnalise,
                'qualidade' => null,
                'id' => '1180239776450916',
                'corpo' => 'Oi {{1}}! Temos um convite para você: {{2}} em {{3}}. Garanta sua vaga: {{4}}',
            ],
        ];
    }
}
