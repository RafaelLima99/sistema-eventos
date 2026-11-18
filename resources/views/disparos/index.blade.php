@extends('layouts.app')

@section('title', 'Disparos')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Disparos</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('disparos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>Novo disparo
        </a>
    </div>
</div>

<div class="card">
    <div class="toolbar d-flex flex-wrap align-items-center">
        <form method="GET" class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
            <div class="search-inline flex-grow-1">
                <i class="bi bi-search"></i>
                <input type="text" name="busca_nome" value="{{ request('busca_nome') }}" placeholder="Buscar pela mensagem...">
            </div>
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-search"></i>Buscar
            </button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th>Mensagem</th>
                    <th>Contatos</th>
                    <th>Envio</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($disparos as $disparo)
                    <tr>
                        <td>
                            <span class="chan-ic wa"><i class="bi bi-whatsapp"></i></span>
                            <span class="strong">{{ $disparo->whatsappMensagem?->titulo ?? '—' }}</span>
                        </td>
                        <td>{{ $disparo->todos_contatos ? 'Todos os contatos' : $disparo->eventos_count.' evento(s)' }}</td>
                        <td>
                            @if ($disparo->agendado_para)
                                <span class="strong">{{ $disparo->agendado_para->format('d/m/Y H:i') }}</span>
                            @else
                                <span class="muted">Envio imediato</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-status {{ $disparo->status->badgeClass() }}"><span class="b-dot"></span>{{ $disparo->status->label() }}</span>
                        </td>
                        <td class="text-end">
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" data-bs-config='{"popperConfig":{"strategy":"fixed"}}'>
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('disparos.edit', $disparo) }}">
                                            <i class="bi bi-pencil"></i>Editar
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('disparos.destroy', $disparo) }}" onsubmit="return confirm('Deseja remover este disparo?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash text-danger"></i>Remover</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Nenhum disparo encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($disparos->hasPages())
        <div class="table-foot d-flex align-items-center">
            {{ $disparos->links() }}
        </div>
    @endif
</div>
@endsection
