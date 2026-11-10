<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Inscrito;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InscritoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // with() busca os contatos e eventos de todos os inscritos de uma vez, em vez de uma consulta por linha.
        $query = Inscrito::query()->with(['contato', 'evento']);

        if ($request->filled('busca_nome')) {
            // O nome está na tabela contatos: whereHas filtra os inscritos pelo contato, e a função diz como filtrar o contato.
            $query->whereHas('contato', function ($contato) use ($request) {
                $contato->whereLike('nome', '%'.$request->string('busca_nome').'%');
            });
        }

        if ($request->filled('evento')) {
            $query->where('evento_id', $request->integer('evento'));
        }

        if ($request->filled('cpf')) {
            // O CPF é salvo sem máscara: "312.048.271-11" vira "31204827111" para achar o contato.
            $cpf = preg_replace('/\D/', '', $request->string('cpf'));
            // O CPF está na tabela contatos: filtra os inscritos cujo contato tem esse CPF.
            $query->whereHas('contato', fn ($contato) => $contato->where('cpf', $cpf));
        }

        if ($request->filled('status_ingresso')) {
            $query->where('status_ingresso', $request->string('status_ingresso'));
        }

        if ($request->filled('status_presenca')) {
            $query->where('status_presenca', $request->string('status_presenca'));
        }

        $inscritos = $query->orderByDesc('id')
            ->paginate(15)
            // Mantém a busca nos links da paginação.
            ->withQueryString();

        return view('inscritos.index', [
            'inscritos' => $inscritos,
            'eventos' => Evento::orderBy('titulo')->get(['id', 'titulo']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inscrito $inscrito): RedirectResponse
    {
        $inscrito->delete();

        return redirect()->route('inscritos.index')->with('sucesso', 'Inscrito removido.');
    }
}
