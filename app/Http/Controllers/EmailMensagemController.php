<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmailMensagemRequest;
use App\Http\Requests\UpdateEmailMensagemRequest;
use App\Models\EmailMensagem;
use App\Services\EmailVariaveisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailMensagemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = EmailMensagem::query();

        if ($request->filled('busca_nome')) {
            $query->whereLike('titulo', '%'.$request->string('busca_nome').'%');
        }

        $emailMensagens = $query->orderByDesc('id')
            ->paginate(15)
            // Mantém a busca nos links da paginação.
            ->withQueryString();

        return view('email-mensagens.index', ['emailMensagens' => $emailMensagens]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(EmailVariaveisService $emailVariaveisService): View
    {
        return view('email-mensagens.create', ['variaveisDisponiveis' => $emailVariaveisService->listarDisponiveis()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmailMensagemRequest $request): RedirectResponse
    {
        EmailMensagem::create($request->validated());

        return redirect()->route('email-mensagens.index')->with('sucesso', 'Mensagem criada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailMensagem $emailMensagem, EmailVariaveisService $emailVariaveisService): View
    {
        return view('email-mensagens.show', [
            'emailMensagem' => $emailMensagem,
            // A lista sai do próprio texto, então nunca fica desatualizada.
            'variaveisUsadas' => $emailVariaveisService->extrairVariaveis($emailMensagem->assunto, $emailMensagem->conteudo),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailMensagem $emailMensagem, EmailVariaveisService $emailVariaveisService): View
    {
        return view('email-mensagens.edit', [
            'emailMensagem' => $emailMensagem,
            'variaveisDisponiveis' => $emailVariaveisService->listarDisponiveis(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmailMensagemRequest $request, EmailMensagem $emailMensagem): RedirectResponse
    {
        $emailMensagem->update($request->validated());

        return redirect()->route('email-mensagens.index')->with('sucesso', 'Mensagem atualizada.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailMensagem $emailMensagem): RedirectResponse
    {
        // O banco não deixa remover uma mensagem que alguma automação usa.
        $totalDeAutomacoes = $emailMensagem->automacoes()->count();

        if ($totalDeAutomacoes > 0) {
            return redirect()->route('email-mensagens.index')
                ->with('erro', "Não é possível remover: a mensagem é usada em {$totalDeAutomacoes} automação(ões).");
        }

        $emailMensagem->delete();

        return redirect()->route('email-mensagens.index')->with('sucesso', 'Mensagem removida.');
    }
}
