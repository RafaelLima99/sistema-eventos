<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContatoRequest;
use App\Models\Contato;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Contato::query();

        if ($request->filled('busca_nome')) {
            $query->whereLike('nome', '%'.$request->string('busca_nome').'%');
        }

        $contatos = $query->orderByDesc('id')
            ->paginate(15)
            // Mantém a busca nos links da paginação.
            ->withQueryString();

        return view('contatos.index', ['contatos' => $contatos]);
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
    public function store(StoreContatoRequest $request): RedirectResponse
    {
        Contato::create($request->validated());

        return redirect()->route('contatos.index')->with('sucesso', 'Contato criado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contato $contato): View
    {
        return view('contatos.show', ['contato' => $contato]);
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
    public function destroy(string $id)
    {
        //
    }
}
