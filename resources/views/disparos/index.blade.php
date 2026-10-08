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
                <input type="text" name="q" placeholder="Buscar disparo...">
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
                    <th>Disparo</th>
                    <th>Canal</th>
                    <th>Envio</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span class="strong">Convite — lote 2</span>
                    </td>
                    <td>
                        <span class="chan-ic wa">
                            <i class="bi bi-whatsapp"></i>
                        </span>
                        WhatsApp
                    </td>
                    <td>
                        Agendado · <span class="strong">12/09 10:00</span>
                    </td>
                    <td>
                        <span class="badge-status bs-amber">
                            <span class="b-dot"></span>Agendado
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('disparos.edit', 1) }}">
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
                    <td>
                        <span class="strong">Reforço de pagamento</span>
                    </td>
                    <td>
                        <span class="chan-ic wa">
                            <i class="bi bi-whatsapp"></i>
                        </span>
                        WhatsApp
                    </td>
                    <td>
                        Enviado · <span class="strong">06/09 15:20</span>
                    </td>
                    <td>
                        <span class="badge-status bs-blue">
                            <span class="b-dot"></span>Concluído
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('disparos.edit', 1) }}">
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
                    <td>
                        <span class="strong">Boas-vindas VIP</span>
                    </td>
                    <td>
                        <span class="chan-ic wa">
                            <i class="bi bi-whatsapp"></i>
                        </span>
                        WhatsApp
                    </td>
                    <td>
                        Enviado · <span class="strong">04/09 09:00</span>
                    </td>
                    <td>
                        <span class="badge-status bs-blue">
                            <span class="b-dot"></span>Concluído
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('disparos.edit', 1) }}">
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
                    <td>
                        <span class="strong">Lembrete véspera (extra)</span>
                    </td>
                    <td>
                        <span class="chan-ic em">
                            <i class="bi bi-envelope"></i>
                        </span>
                        E-mail
                    </td>
                    <td>
                        Agendado · <span class="strong">17/09 20:00</span>
                    </td>
                    <td>
                        <span class="badge-status bs-amber">
                            <span class="b-dot"></span>Agendado
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('disparos.edit', 1) }}">
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
                    <td>
                        <span class="strong">Instruções de estacionamento</span>
                    </td>
                    <td>
                        <span class="chan-ic wa">
                            <i class="bi bi-whatsapp"></i>
                        </span>
                        WhatsApp
                    </td>
                    <td class="muted">— a definir</td>
                    <td>
                        <span class="badge-status bs-slate">
                            <span class="b-dot"></span>Rascunho
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('disparos.edit', 1) }}">
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
                    <td>
                        <span class="strong">Pesquisa pós-evento</span>
                    </td>
                    <td>
                        <span class="chan-ic em">
                            <i class="bi bi-envelope"></i>
                        </span>
                        E-mail
                    </td>
                    <td>
                        Agendado · <span class="strong">19/09 10:00</span>
                    </td>
                    <td>
                        <span class="badge-status bs-amber">
                            <span class="b-dot"></span>Agendado
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('disparos.edit', 1) }}">
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
                    <td>
                        <span class="strong">Agradecimento + gravações</span>
                    </td>
                    <td>
                        <span class="chan-ic em">
                            <i class="bi bi-envelope"></i>
                        </span>
                        E-mail
                    </td>
                    <td>
                        Agendado · <span class="strong">20/09 09:00</span>
                    </td>
                    <td>
                        <span class="badge-status bs-slate">
                            <span class="b-dot"></span>Rascunho
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('disparos.edit', 1) }}">
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
                    <td>
                        <span class="strong">Aviso de mudança de sala</span>
                    </td>
                    <td>
                        <span class="chan-ic wa">
                            <i class="bi bi-whatsapp"></i>
                        </span>
                        WhatsApp
                    </td>
                    <td>
                        Enviado · <span class="strong">05/09 11:10</span>
                    </td>
                    <td>
                        <span class="badge-status bs-blue">
                            <span class="b-dot"></span>Concluído
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('disparos.edit', 1) }}">
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
        <nav class="ms-auto" aria-label="Paginação de disparos">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
