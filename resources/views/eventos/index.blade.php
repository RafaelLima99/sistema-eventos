@extends('layouts.app')

@section('title', 'Eventos')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Eventos</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <form method="GET" class="d-flex flex-wrap gap-2">
            <div class="search-inline position-relative flex-grow-0 flex-shrink-0" style="flex-basis:260px;max-width:260px">
                <i class="bi bi-search position-absolute"></i>
                <input type="text" name="busca_nome" value="{{ request('busca_nome') }}" class="w-100" placeholder="Buscar evento por nome..." style="height:40px">
            </div>
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i>Buscar</button>
        </form>
        <a href="{{ route('eventos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i>Novo evento</a>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th>Evento</th>
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Local</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($eventos as $evento)
                    <tr>
                        <td>
                            <span class="strong">{{ $evento->titulo }}</span>
                        </td>
                        <td>{{ $evento->data_evento->format('d/m/Y') }}</td>
                        <td>{{ $evento->hora_inicio }}@if ($evento->hora_fim) – {{ $evento->hora_fim }}@endif</td>
                        <td>{{ $evento->local }}<br><small class="muted">{{ $evento->cidade }}, {{ $evento->uf->value }}</small></td>
                        <td><span class="badge-status {{ $evento->status->badgeClass() }}"><span class="b-dot"></span>{{ $evento->status->label() }}</span></td>
                        <td class="text-end">
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" data-bs-config='{"popperConfig":{"strategy":"fixed"}}'><i class="bi bi-three-dots"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('eventos.show', $evento) }}"><i class="bi bi-eye"></i>Ver detalhes</a></li>
                                    <li><a class="dropdown-item" href="{{ route('inscritos.index') }}"><i class="bi bi-people"></i>Inscritos</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('eventos.edit', $evento) }}"><i class="bi bi-pencil"></i>Editar</a></li>
                                    <li><a class="dropdown-item" href="{{ route('eventos.create') }}"><i class="bi bi-files"></i>Duplicar</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('eventos.destroy', $evento) }}" onsubmit="return confirm(@js('Deseja excluir "'.$evento->titulo.'"?'))">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash text-danger"></i>Excluir</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Nenhum evento encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($eventos->hasPages())
        <div class="table-foot d-flex align-items-center">
            {{ $eventos->links() }}
        </div>
    @endif
</div>
@endsection
