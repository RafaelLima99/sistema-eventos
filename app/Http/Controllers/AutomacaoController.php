<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAutomacaoRequest;
use App\Http\Requests\UpdateAutomacaoRequest;
use App\Models\Automacao;
use App\Models\EmailMensagem;
use App\Models\Evento;
use App\Models\WhatsappMensagem;
use App\Services\AutomacaoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AutomacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // with() busca o evento e as mensagens de todas as automações de uma vez, em vez de uma consulta por automação.
        $query = Automacao::query()->with(['evento', 'whatsappMensagem', 'emailMensagem']);

        if ($request->filled('evento')) {
            $query->where('evento_id', $request->integer('evento'));
        }

        $automacoes = $query->orderBy('minutos_disparo')
            ->orderBy('id')
            ->paginate(15)
            // Mantém o filtro nos links da paginação.
            ->withQueryString();

        return view('automacoes.index', [
            'automacoes' => $automacoes,
            // Eventos do filtro, em ordem alfabética. Busca só id e título, que é o que o select usa.
            'eventos' => Evento::orderBy('titulo')->get(['id', 'titulo']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('automacoes.create', $this->opcoesDoFormulario());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAutomacaoRequest $request, AutomacaoService $automacaoService): RedirectResponse
    {
        Automacao::create($automacaoService->prepararDados($request->validated()));

        return redirect()->route('automacoes.index')->with('sucesso', 'Automação criada.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Automacao $automacao): View
    {
        // Os "..." juntam ao array as listas de opcoesDoFormulario() (eventos e mensagens), ao lado da automação.
        return view('automacoes.edit', ['automacao' => $automacao, ...$this->opcoesDoFormulario()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAutomacaoRequest $request, Automacao $automacao, AutomacaoService $automacaoService): RedirectResponse
    {
        $automacao->update($automacaoService->prepararDados($request->validated()));

        return redirect()->route('automacoes.index')->with('sucesso', 'Automação atualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Automacao $automacao): RedirectResponse
    {
        $automacao->delete();

        return redirect()->route('automacoes.index')->with('sucesso', 'Automação removida.');
    }

    /**
     * Listas que preenchem os selects do formulário (criação e edição).
     *
     * @return array<string, mixed>
     */
    private function opcoesDoFormulario(): array
    {
        return [
            'eventos' => Evento::orderBy('titulo')->get(['id', 'titulo']),
            'whatsappMensagens' => WhatsappMensagem::orderBy('titulo')->get(['id', 'titulo']),
            'emailMensagens' => EmailMensagem::orderBy('titulo')->get(['id', 'titulo']),
        ];
    }
}
