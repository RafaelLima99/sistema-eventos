<?php

namespace App\Http\Controllers;

use App\Enums\StatusEvento;
use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Models\Evento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Evento::query();

        if ($request->filled('busca_nome')) {
            $query->whereLike('titulo', '%'.$request->string('busca_nome').'%');
        }

        $eventos = $query->orderByDesc('id')
            ->paginate(15)
            // Mantém a busca nos links da paginação.
            ->withQueryString();

        return view('eventos.index', ['eventos' => $eventos]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('eventos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventoRequest $request): RedirectResponse
    {
        Evento::create([
            ...$request->validated(),
            'status' => StatusEvento::Publicado,
        ]);

        return redirect()->route('eventos.index')->with('sucesso', 'Evento criado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Evento $evento): View
    {
        return view('eventos.show', ['evento' => $evento]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evento $evento): View
    {
        return view('eventos.edit', ['evento' => $evento]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventoRequest $request, Evento $evento): RedirectResponse
    {
        $evento->update($request->validated());

        return redirect()->route('eventos.index')->with('sucesso', 'Evento atualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evento $evento): RedirectResponse
    {
        $evento->delete();

        return redirect()->route('eventos.index')->with('sucesso', 'Evento removido.');
    }
}
