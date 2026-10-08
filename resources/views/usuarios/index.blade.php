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
                <input type="text" name="q" placeholder="Buscar por nome">
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
                <tr>
                    <td><div class="u-cell"><span class="avatar" style="background:#2563eb">RL</span><b>Rafael Lima</b></div></td>
                    <td>ferramentas@unu.com.br</td>
                    <td><span class="badge-status bs-blue"><span class="b-dot"></span>Admin</span></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('usuarios.edit', 1) }}">
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
                    <td><div class="u-cell"><span class="avatar" style="background:#0f766e">MC</span><b>Marina Costa</b></div></td>
                    <td>marina.costa@unu.com.br</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Operação</span></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('usuarios.edit', 1) }}">
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
                    <td><div class="u-cell"><span class="avatar" style="background:#7c3aed">BA</span><b>Bruno Alves</b></div></td>
                    <td>bruno.alves@unu.com.br</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Operação</span></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('usuarios.edit', 1) }}">
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
                    <td><div class="u-cell"><span class="avatar" style="background:#db2777">CR</span><b>Camila Rocha</b></div></td>
                    <td>camila.rocha@unu.com.br</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Somente leitura</span></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('usuarios.edit', 1) }}">
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
        <nav class="ms-auto" aria-label="Paginação de usuários">
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
