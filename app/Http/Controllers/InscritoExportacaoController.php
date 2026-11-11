<?php

namespace App\Http\Controllers;

use App\Services\InscritoBuscaService;
use App\Services\InscritoExportacaoService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InscritoExportacaoController extends Controller
{
    /**
     * Baixa em CSV os inscritos da busca atual (mesmos filtros da listagem).
     */
    public function index(Request $request, InscritoBuscaService $inscritoBuscaService, InscritoExportacaoService $inscritoExportacaoService): StreamedResponse
    {
        $query = $inscritoBuscaService->buscar($request);

        // Nome do arquivo que o navegador vai salvar, ex.: "inscritos-2026-11-11.csv".
        $nomeDoArquivo = 'inscritos-'.now()->format('Y-m-d').'.csv';

        // streamDownload manda o arquivo para o navegador baixar, escrevendo aos poucos em vez de montar tudo na memória.
        return response()->streamDownload(
            function () use ($query, $inscritoExportacaoService) {
                $saida = fopen('php://output', 'w');

                $inscritoExportacaoService->escrever($query, $saida);

                fclose($saida);
            },
            $nomeDoArquivo,
            ['Content-Type' => 'text/csv; charset=UTF-8'],
        );
    }
}
