<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDisparoRequest;
use App\Http\Requests\UpdateDisparoRequest;
use App\Models\Disparo;
use App\Models\Evento;
use App\Models\WhatsappMensagem;
use App\Services\DisparoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DisparoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // with() busca as mensagens e a contagem de eventos de todos os disparos de uma vez, em vez de uma consulta por linha.
        $query = Disparo::query()->with('whatsappMensagem')->withCount('eventos');

        if ($request->filled('busca_nome')) {
            // O disparo não tem nome próprio: a busca é pelo nome da mensagem de WhatsApp.
            $query->whereHas('whatsappMensagem', function ($mensagem) use ($request) {
                $mensagem->whereLike('titulo', '%'.$request->string('busca_nome').'%');
            });
        }

        $disparos = $query->orderByDesc('id')
            ->paginate(15)
            // Mantém a busca nos links da paginação.
            ->withQueryString();

        return view('disparos.index', ['disparos' => $disparos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('disparos.create', $this->opcoesDoFormulario());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDisparoRequest $request, DisparoService $disparoService): RedirectResponse
    {
        // ->except('eventos') remove o campo eventos dos dados validados, pois ele não vai para a tabela disparos (é salvo no sync abaixo).
        $disparo = Disparo::create($disparoService->prepararDados($request->safe()->except('eventos')));

        // sync: busca os eventos que o disparo já tem salvos na tabela disparo_evento,
        // compara com a lista de eventos marcados no formulário,
        // apaga os que não estão mais na lista e insere os que faltam.
        // Se a opção "Todos os contatos" foi escolhida, a lista vem vazia e nenhum evento é guardado.
        $disparo->eventos()->sync($request->validated('eventos', []));

        return redirect()->route('disparos.index')->with('sucesso', 'Disparo salvo.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Disparo $disparo): View
    {
        // Os "..." juntam ao array as listas de opcoesDoFormulario() (eventos e mensagens), ao lado do disparo.
        return view('disparos.edit', ['disparo' => $disparo, ...$this->opcoesDoFormulario()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDisparoRequest $request, Disparo $disparo, DisparoService $disparoService): RedirectResponse
    {
        // ->except('eventos') remove o campo eventos dos dados validados, pois ele não vai para a tabela disparos (é salvo no sync abaixo).
        $disparo->update($disparoService->prepararDados($request->safe()->except('eventos')));

        // sync: busca os eventos que o disparo já tem salvos na tabela disparo_evento,
        // compara com a lista de eventos marcados no formulário,
        // apaga os que não estão mais na lista e insere os que faltam.
        // Se a opção "Todos os contatos" foi escolhida, a lista vem vazia e todos os eventos do disparo são removidos.
        $disparo->eventos()->sync($request->validated('eventos', []));

        return redirect()->route('disparos.index')->with('sucesso', 'Disparo atualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Disparo $disparo): RedirectResponse
    {
        $disparo->delete();

        return redirect()->route('disparos.index')->with('sucesso', 'Disparo removido.');
    }

    /**
     * Listas que preenchem o formulário (criação e edição).
     *
     * @return array<string, mixed>
     */
    private function opcoesDoFormulario(): array
    {
        return [
            'eventos' => Evento::orderBy('titulo')->get(['id', 'titulo']),
            'whatsappMensagens' => WhatsappMensagem::orderBy('titulo')->get(['id', 'titulo']),
        ];
    }
}
