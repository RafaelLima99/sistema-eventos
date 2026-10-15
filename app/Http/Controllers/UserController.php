<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('busca_nome')) {
            $query->whereLike('name', '%'.$request->string('busca_nome').'%');
        }

        $usuarios = $query->orderBy('name')
            ->paginate(15
            )
            // Mantém a busca nos links da paginação.
            ->withQueryString();

        return view('usuarios.index', ['usuarios' => $usuarios]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('usuarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('usuarios.index')->with('sucesso', 'Usuário criado.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $usuario): View
    {
        return view('usuarios.edit', ['usuario' => $usuario]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $usuario): RedirectResponse
    {
        $dados = $request->validated();

        if (blank($dados['password'] ?? null)) {
            // remove o campo password do array
            unset($dados['password']);
        }

        $usuario->update($dados);

        return redirect()->route('usuarios.index')->with('sucesso', 'Usuário atualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $usuario): RedirectResponse
    {
        if ($usuario->is($request->user())) {
            return redirect()->route('usuarios.index')->with('erro', 'Você não pode remover o próprio usuário.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('sucesso', 'Usuário removido.');
    }
}
