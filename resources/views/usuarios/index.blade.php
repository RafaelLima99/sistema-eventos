@extends('layouts.app')

@section('title', 'Usuários')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Usuários</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i>Novo usuário</a>
    </div>
</div>

<div class="card">
    <div class="toolbar d-flex flex-wrap align-items-center">
        <form method="GET" class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
            <div class="search-inline flex-grow-1">
                <i class="bi bi-search"></i>
                <input type="text" name="busca_nome" value="{{ request('busca_nome') }}" placeholder="Buscar por nome">
            </div>
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i>Buscar</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>E-mail</th>
                    <th>Papel</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td>
                            <div class="u-cell">
                                <span class="avatar avatar-cor-{{ $usuario->id % 6 }}">{{ $usuario->iniciaisDoNome() }}</span>
                                <b>{{ $usuario->name }}</b>
                            </div>
                        </td>
                        <td>{{ $usuario->email }}</td>
                        <td>—</td>
                        <td class="text-end">
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" data-bs-config='{"popperConfig":{"strategy":"fixed"}}'>
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('usuarios.edit', $usuario) }}">
                                            <i class="bi bi-pencil"></i>Editar
                                        </a>
                                    </li>
                                    @if ($usuario->isNot(auth()->user()))
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" onsubmit="return confirm(@js('Deseja remover “'.$usuario->name.'”?'))">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash text-danger"></i>Remover
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Nenhum usuário encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($usuarios->hasPages())
        <div class="table-foot d-flex align-items-center">
            {{ $usuarios->links() }}
        </div>
    @endif
</div>
@endsection
