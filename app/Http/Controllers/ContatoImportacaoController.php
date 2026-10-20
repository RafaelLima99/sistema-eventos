<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContatoImportacaoRequest;
use App\Services\ContatoImportacaoService;
use Illuminate\Http\RedirectResponse;
use InvalidArgumentException;

class ContatoImportacaoController extends Controller
{
    /**
     * Importa contatos de um arquivo CSV.
     */
    public function store(StoreContatoImportacaoRequest $request, ContatoImportacaoService $contatoImportacaoService): RedirectResponse
    {
        try {
            $resultado = $contatoImportacaoService->importar($request->file('arquivo')->getRealPath());
        } catch (InvalidArgumentException $erro) {
            return back()->withErrors(['arquivo' => $erro->getMessage()], 'importacao');
        }

        $redirect = redirect()->route('contatos.index')
            ->with('sucesso', "Importação concluída: {$resultado['criados']} criado(s) e {$resultado['atualizados']} atualizado(s).");

        if ($resultado['ignoradas'] !== []) {
            $redirect->with('erro', count($resultado['ignoradas']).' linha(s) ignorada(s) por dados inválidos: '.implode(', ', $resultado['ignoradas']).'.');
        }

        return $redirect;
    }
}
