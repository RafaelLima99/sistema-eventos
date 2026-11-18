<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Templates de WhatsApp aprovados pela Meta (WhatsApp Manager), buscados pela API oficial (WhatsApp Cloud API).
 * Só templates aprovados podem ser enviados, por isso o sistema nem busca os outros.
 *
 * Credenciais no .env: WHATSAPP_TOKEN e WHATSAPP_BUSINESS_ACCOUNT_ID (ver config/services.php).
 */
class WhatsappTemplateService
{
    /**
     * Por quanto tempo (em segundos) a lista da Meta fica guardada antes de buscar de novo.
     * Evita chamar a Meta a cada página aberta. Template aprovado agora aparece em até 5 minutos.
     */
    private const SEGUNDOS_EM_CACHE = 300;

    /**
     * Templates aprovados da conta, indexados pelo nome do template.
     *
     * Cada template tem: nome, categoria, categoria_classe, idioma, qualidade,
     * id, corpo (texto com {{1}}, {{2}}…) e total_variaveis.
     * Se a Meta não responder, devolve lista vazia (e registra o erro no log).
     *
     * @return array<string, array<string, mixed>>
     */
    public function listarAprovados(): array
    {
        try {
            // Guarda a lista por até 5 minutos com o nome "whatsapp_templates" (tabela cache do banco).
            // Se já está guardada, devolve sem chamar a Meta. Se expirou, chama buscarNaMeta() e guarda de novo.
            // Por isso, um template aprovado agora na Meta pode levar até 5 minutos para aparecer no sistema.
            return Cache::remember('whatsapp_templates', self::SEGUNDOS_EM_CACHE, fn () => $this->buscarNaMeta());
        } catch (ConnectionException|RequestException $erro) {
            report($erro);

            return [];
        }
    }

    /**
     * @return array<string, mixed>|null o template, ou null se não estiver aprovado (ou não existir) na Meta
     */
    public function buscarAprovadoPorNome(?string $nome): ?array
    {
        return $this->listarAprovados()[$nome] ?? null;
    }

    /**
     * Chama a API da Meta e converte cada template para o formato usado no sistema.
     *
     * @return array<string, array<string, mixed>>
     */
    private function buscarNaMeta(): array
    {
        $versao = config('services.whatsapp.versao');
        $contaId = config('services.whatsapp.business_account_id');

        $url = "https://graph.facebook.com/{$versao}/{$contaId}/message_templates";
        // status=APPROVED: a própria Meta filtra e devolve só os templates aprovados.
        $parametros = ['status' => 'APPROVED', 'fields' => 'id,name,language,category,quality_score,components', 'limit' => 100];
        $templates = [];

        // A Meta devolve no máximo 100 por vez; "paging.next" traz o link da próxima página (some na última).
        while ($url) {
            // Http:: é o cliente do Laravel para chamar APIs externas (aqui, a da Meta).
            $resposta = Http::withToken(config('services.whatsapp.token')) // Envia o token: prova que o sistema pode ver os templates da conta.
                ->connectTimeout(3) // Espera no máximo 3s para conectar.
                ->timeout(10) // Espera no máximo 10s pela resposta, para a página não ficar travada.
                ->get($url, $parametros) // Faz a requisição GET; os parâmetros vão na URL (?status=APPROVED&...).
                ->throw() // Se a Meta responder com erro, lança exceção (tratada em listarAprovados()).
                ->json(); // Converte a resposta JSON em array.

            foreach ($resposta['data'] ?? [] as $templateDaMeta) {
                $templates[$templateDaMeta['name']] = $this->converter($templateDaMeta);
            }

            $url = $resposta['paging']['next'] ?? null;
            $parametros = []; // O link da próxima página já vem com os parâmetros.
        }

        return $templates;
    }

    /**
     * Converte um template no formato da Meta para o formato do sistema.
     *
     * @param  array<string, mixed>  $templateDaMeta
     * @return array<string, mixed>
     */
    private function converter(array $templateDaMeta): array
    {
        // O texto principal fica no componente BODY. Cabeçalho, rodapé e botões não são usados aqui.
        $corpo = collect($templateDaMeta['components'] ?? [])->firstWhere('type', 'BODY')['text'] ?? '';

        return [
            'nome' => $templateDaMeta['name'],
            'categoria' => match ($templateDaMeta['category'] ?? null) {
                'MARKETING' => 'Marketing',
                'AUTHENTICATION' => 'Autenticação',
                default => 'Utilidade',
            },
            'categoria_classe' => ($templateDaMeta['category'] ?? null) === 'MARKETING' ? 'mkt' : 'util',
            'idioma' => $templateDaMeta['language'] ?? '',
            'qualidade' => match ($templateDaMeta['quality_score']['score'] ?? null) {
                'GREEN' => 'Alta',
                'YELLOW' => 'Média',
                'RED' => 'Baixa',
                default => null,
            },
            'id' => $templateDaMeta['id'],
            'corpo' => $corpo,
            'total_variaveis' => $this->contarVariaveis($corpo),
        ];
    }

    /**
     * Quantidade de variáveis do template: o maior número usado no texto ("Olá {{1}}, {{2}}" => 2).
     */
    private function contarVariaveis(string $corpo): int
    {
        preg_match_all('/\{\{(\d+)\}\}/', $corpo, $encontradas);

        return $encontradas[1] === [] ? 0 : max(array_map('intval', $encontradas[1]));
    }
}
