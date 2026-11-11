<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Inscrito;
use App\Services\InscritoBuscaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InscritoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, InscritoBuscaService $inscritoBuscaService): View
    {
        $query = $inscritoBuscaService->buscar($request);

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
