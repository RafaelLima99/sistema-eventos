@extends('layouts.app')

@section('title', 'Mensagens de e-mail')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Mensagens E-mail</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('email-mensagens.create') }}" class="btn btn-primary"><i class="bi bi-envelope"></i>Nova mensagem de E-mail</a>
    </div>
</div>

<div class="seg mb-3">
    <a class="d-inline-flex align-items-center gap-2" href="{{ route('whatsapp-mensagens.index') }}">
        <i class="bi bi-whatsapp"></i>WhatsApp
    </a>
    <a class="active d-inline-flex align-items-center gap-2" href="{{ route('email-mensagens.index') }}">
        <i class="bi bi-envelope"></i>E-mail
    </a>
</div>

<div class="card">
    <div class="toolbar d-flex flex-wrap align-items-center">
        <form method="GET" class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
            <div class="search-inline flex-grow-1">
                <i class="bi bi-search"></i>
                <input type="text" name="busca_nome" value="{{ request('busca_nome') }}" placeholder="Buscar mensagem">
            </div>
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i>Buscar</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th>Mensagem</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($emailMensagens as $emailMensagem)
                    <tr>
                        <td><a href="{{ route('email-mensagens.show', $emailMensagem) }}" class="strong">{{ $emailMensagem->titulo }}</a></td>
                        <td class="text-end">
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" data-bs-config='{"popperConfig":{"strategy":"fixed"}}'>
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('email-mensagens.show', $emailMensagem) }}">
                                            <i class="bi bi-eye"></i>Detalhes
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('email-mensagens.edit', $emailMensagem) }}">
                                            <i class="bi bi-pencil"></i>Editar
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('email-mensagens.destroy', $emailMensagem) }}" onsubmit="return confirm(@js('Deseja remover "'.$emailMensagem->titulo.'"?'))">
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
                        <td colspan="2" class="text-center text-muted py-4">Nenhuma mensagem encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($emailMensagens->hasPages())
        <div class="table-foot d-flex align-items-center">
            {{ $emailMensagens->links() }}
        </div>
    @endif
</div>
@endsection
