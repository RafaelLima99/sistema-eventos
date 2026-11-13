@extends('layouts.app')

@section('title', 'Mensagens de WhatsApp')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Mensagens WhatsApp</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('whatsapp-mensagens.create') }}" class="btn btn-primary"><i class="bi bi-whatsapp"></i>Nova mensagem de WhatsApp</a>
    </div>
</div>

<div class="seg mb-3">
    <a class="active d-inline-flex align-items-center gap-2" href="{{ route('whatsapp-mensagens.index') }}">
        <i class="bi bi-whatsapp"></i>WhatsApp
    </a>
    <a class="d-inline-flex align-items-center gap-2" href="{{ route('email-mensagens.index') }}">
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
                    <th>Status na Meta</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($whatsappMensagens as $whatsappMensagem)
                    @php($status = $templates[$whatsappMensagem->nome_template_meta]['status'] ?? null)
                    <tr>
                        <td><a href="{{ route('whatsapp-mensagens.show', $whatsappMensagem) }}" class="strong">{{ $whatsappMensagem->titulo }}</a></td>
                        <td>
                            @if ($status)
                                <span class="badge-status {{ $status->badgeClass() }}"><span class="b-dot"></span>{{ $status->label() }}</span>
                            @else
                                <span class="muted">—</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" data-bs-config='{"popperConfig":{"strategy":"fixed"}}'>
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('whatsapp-mensagens.show', $whatsappMensagem) }}">
                                            <i class="bi bi-eye"></i>Detalhes
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('whatsapp-mensagens.edit', $whatsappMensagem) }}">
                                            <i class="bi bi-pencil"></i>Editar
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('whatsapp-mensagens.destroy', $whatsappMensagem) }}" onsubmit="return confirm(@js('Deseja remover "'.$whatsappMensagem->titulo.'"?'))">
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
                        <td colspan="3" class="text-center text-muted py-4">Nenhuma mensagem encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($whatsappMensagens->hasPages())
        <div class="table-foot d-flex align-items-center">
            {{ $whatsappMensagens->links() }}
        </div>
    @endif
</div>
@endsection
