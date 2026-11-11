<?php

namespace App\Http\Controllers;

use App\Enums\StatusPresenca;
use App\Models\Contato;
use App\Models\Evento;
use App\Models\Inscrito;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresencaController extends Controller
{
    /**
     * Lista os inscritos de um evento para o credenciamento.
     *
     * Sem evento escolhido, mostra o próximo evento (ou o mais recente, se todos já passaram).
     */
    public function index(Request $request): View
    {
        $eventos = Evento::orderBy('titulo')->get(['id', 'titulo']);

        // Define qual evento a tela mostra. Cada linha abaixo é uma opção, e vale a primeira que existir:
        // linha 1: o evento escolhido no filtro (vem na URL, ex.: ?evento=5);
        // linha 2: sem filtro, o evento mais próximo, de hoje em diante;
        // linha 3: se todos já passaram, o evento mais recente.
        $evento = $eventos->firstWhere('id', $request->integer('evento'))
            ?? Evento::where('data_evento', '>=', today())->orderBy('data_evento')->first()
            ?? Evento::latest('data_evento')->first();

        // Sem nenhum evento cadastrado usa o id 0, que não existe e devolve a lista vazia.
        $query = Inscrito::query()
            ->with('contato')
            ->where('evento_id', $evento?->id ?? 0);

        if ($request->filled('busca_nome')) {
            // O nome está na tabela contatos: whereHas filtra os inscritos pelo contato, e a função diz como filtrar o contato.
            $query->whereHas('contato', function ($contato) use ($request) {
                $contato->whereLike('nome', '%'.$request->string('busca_nome').'%');
            });
        }

        if ($request->filled('cpf')) {
            // O CPF é salvo sem máscara: "312.048.271-11" vira "31204827111" para achar o contato.
            $cpf = preg_replace('/\D/', '', $request->string('cpf'));
            // O CPF está na tabela contatos: filtra os inscritos cujo contato tem esse CPF.
            $query->whereHas('contato', fn ($contato) => $contato->where('cpf', $cpf));
        }

        $inscritos = $query
            // Ordem alfabética pelo nome do contato, que está em outra tabela (por isso a subconsulta).
            ->orderBy(Contato::select('nome')->whereColumn('contatos.id', 'inscritos.contato_id'))
            ->orderBy('id')
            ->paginate(15)
            // Mantém a busca nos links da paginação.
            ->withQueryString();

        return view('presenca.index', [
            'inscritos' => $inscritos,
            'eventos' => $eventos,
            'evento' => $evento,
        ]);
    }

    /**
     * Confirma a presença do inscrito e volta para a mesma página da lista, com os filtros que estavam aplicados.
     */
    public function store(Inscrito $inscrito): RedirectResponse
    {
        $inscrito->update(['status_presenca' => StatusPresenca::Presente]);

        return back()->with('sucesso', "Presença de {$inscrito->contato->nome} confirmada.");
    }
}
