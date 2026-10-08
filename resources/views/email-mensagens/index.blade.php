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
                <input type="text" name="q" placeholder="Buscar mensagem">
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
                <tr>
                    <td><a href="{{ route('email-mensagens.show', 1) }}" class="strong">Confirmação de inscrição</a></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('email-mensagens.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('email-mensagens.edit', 1) }}">
                                        <i class="bi bi-pencil"></i>Editar
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Remover
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><a href="{{ route('email-mensagens.show', 1) }}" class="strong">Lembrete de pagamento (15 min)</a></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('email-mensagens.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('email-mensagens.edit', 1) }}">
                                        <i class="bi bi-pencil"></i>Editar
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Remover
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><a href="{{ route('email-mensagens.show', 1) }}" class="strong">Última chamada de pagamento (1h)</a></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('email-mensagens.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('email-mensagens.edit', 1) }}">
                                        <i class="bi bi-pencil"></i>Editar
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Remover
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><a href="{{ route('email-mensagens.show', 1) }}" class="strong">Lembrete — 1 dia antes</a></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('email-mensagens.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('email-mensagens.edit', 1) }}">
                                        <i class="bi bi-pencil"></i>Editar
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Remover
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><a href="{{ route('email-mensagens.show', 1) }}" class="strong">Pós-evento — pesquisa</a></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('email-mensagens.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('email-mensagens.edit', 1) }}">
                                        <i class="bi bi-pencil"></i>Editar
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Remover
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-foot d-flex align-items-center">
        <nav class="ms-auto" aria-label="Paginação de mensagens de e-mail">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-chevron-left"></i></a>
                </li>
                <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                <li class="page-item">
                    <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
