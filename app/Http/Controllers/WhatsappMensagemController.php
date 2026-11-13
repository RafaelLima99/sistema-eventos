<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWhatsappMensagemRequest;
use App\Http\Requests\UpdateWhatsappMensagemRequest;
use App\Models\WhatsappMensagem;
use App\Services\WhatsappTemplateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhatsappMensagemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, WhatsappTemplateService $whatsappTemplateService): View
    {
        $query = WhatsappMensagem::query();

        if ($request->filled('busca_nome')) {
            $query->whereLike('titulo', '%'.$request->string('busca_nome').'%');
        }

        $whatsappMensagens = $query->orderByDesc('id')
            ->paginate(15)
            // Mantém a busca nos links da paginação.
            ->withQueryString();

        return view('whatsapp-mensagens.index', [
            'whatsappMensagens' => $whatsappMensagens,
            'templates' => $whatsappTemplateService->listar(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(WhatsappTemplateService $whatsappTemplateService): View
    {
        return view('whatsapp-mensagens.create', ['templates' => $whatsappTemplateService->listarAprovados()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWhatsappMensagemRequest $request): RedirectResponse
    {
        WhatsappMensagem::create($request->validated());

        return redirect()->route('whatsapp-mensagens.index')->with('sucesso', 'Mensagem criada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WhatsappMensagem $whatsappMensagem, WhatsappTemplateService $whatsappTemplateService): View
    {
        return view('whatsapp-mensagens.show', [
            'whatsappMensagem' => $whatsappMensagem,
            // Pode ser null se o template deixou de existir na Meta.
            'template' => $whatsappTemplateService->buscar($whatsappMensagem->nome_template_meta),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WhatsappMensagem $whatsappMensagem, WhatsappTemplateService $whatsappTemplateService): View
    {
        return view('whatsapp-mensagens.edit', [
            'whatsappMensagem' => $whatsappMensagem,
            'templates' => $whatsappTemplateService->listarAprovados(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWhatsappMensagemRequest $request, WhatsappMensagem $whatsappMensagem): RedirectResponse
    {
        $whatsappMensagem->update($request->validated());

        return redirect()->route('whatsapp-mensagens.index')->with('sucesso', 'Mensagem atualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WhatsappMensagem $whatsappMensagem): RedirectResponse
    {
        // O banco não deixa remover uma mensagem que alguma automação ou disparo usa.
        $totalDeAutomacoes = $whatsappMensagem->automacoes()->count();
        $totalDeDisparos = $whatsappMensagem->disparos()->count();

        if ($totalDeAutomacoes + $totalDeDisparos > 0) {
            return redirect()->route('whatsapp-mensagens.index')
                ->with('erro', "Não é possível remover: a mensagem é usada em {$totalDeAutomacoes} automação(ões) e {$totalDeDisparos} disparo(s).");
        }

        $whatsappMensagem->delete();

        return redirect()->route('whatsapp-mensagens.index')->with('sucesso', 'Mensagem removida.');
    }
}
