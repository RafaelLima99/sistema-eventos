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
                <input type="text" name="q" class="w-100" placeholder="Buscar evento por nome..." style="height:40px">
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
                <tr>
                    <td>
                        <span class="strong">Summit de Marketing Digital 2026</span>
                    </td>
                    <td>18/09/2026</td>
                    <td>09:00 – 18:00</td>
                    <td>C. Convenções Frei Caneca<br><small class="muted">São Paulo, SP</small></td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Publicado</span></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('eventos.show', 1) }}"><i class="bi bi-eye"></i>Ver detalhes</a></li>
                                <li><a class="dropdown-item" href="{{ route('inscritos.index') }}"><i class="bi bi-people"></i>Inscritos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.edit', 1) }}"><i class="bi bi-pencil"></i>Editar</a></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.create') }}"><i class="bi bi-files"></i>Duplicar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash text-danger"></i>Excluir</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="strong">Workshop de Vendas B2B — Turma 12</span>
                    </td>
                    <td>25/09/2026</td>
                    <td>19:00 – 22:00</td>
                    <td>Auditório UNU Paulista<br><small class="muted">São Paulo, SP</small></td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Publicado</span></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('eventos.show', 1) }}"><i class="bi bi-eye"></i>Ver detalhes</a></li>
                                <li><a class="dropdown-item" href="{{ route('inscritos.index') }}"><i class="bi bi-people"></i>Inscritos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.edit', 1) }}"><i class="bi bi-pencil"></i>Editar</a></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.create') }}"><i class="bi bi-files"></i>Duplicar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash text-danger"></i>Excluir</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="strong">Meetup IA para Negócios</span>
                    </td>
                    <td>02/10/2026</td>
                    <td>18:30 – 21:30</td>
                    <td>Cubo Itaú<br><small class="muted">São Paulo, SP</small></td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Rascunho</span></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('eventos.show', 1) }}"><i class="bi bi-eye"></i>Ver detalhes</a></li>
                                <li><a class="dropdown-item" href="{{ route('inscritos.index') }}"><i class="bi bi-people"></i>Inscritos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.edit', 1) }}"><i class="bi bi-pencil"></i>Editar</a></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.create') }}"><i class="bi bi-files"></i>Duplicar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash text-danger"></i>Excluir</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="strong">Conferência de Produto &amp; Growth</span>
                    </td>
                    <td>15/10/2026</td>
                    <td>09:00 – 17:00</td>
                    <td>Teatro B32<br><small class="muted">São Paulo, SP</small></td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Publicado</span></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('eventos.show', 1) }}"><i class="bi bi-eye"></i>Ver detalhes</a></li>
                                <li><a class="dropdown-item" href="{{ route('inscritos.index') }}"><i class="bi bi-people"></i>Inscritos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.edit', 1) }}"><i class="bi bi-pencil"></i>Editar</a></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.create') }}"><i class="bi bi-files"></i>Duplicar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash text-danger"></i>Excluir</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="strong">Bootcamp de Dados — Imersão 1 dia</span>
                    </td>
                    <td>08/11/2026</td>
                    <td>08:30 – 18:30</td>
                    <td>Espaço Soft — Vila Madalena<br><small class="muted">São Paulo, SP</small></td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Publicado</span></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('eventos.show', 1) }}"><i class="bi bi-eye"></i>Ver detalhes</a></li>
                                <li><a class="dropdown-item" href="{{ route('inscritos.index') }}"><i class="bi bi-people"></i>Inscritos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.edit', 1) }}"><i class="bi bi-pencil"></i>Editar</a></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.create') }}"><i class="bi bi-files"></i>Duplicar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash text-danger"></i>Excluir</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="strong">Café com Founders — Edição Setembro</span>
                    </td>
                    <td>14/08/2026</td>
                    <td>08:00 – 10:00</td>
                    <td>Coworking Vitrúvio<br><small class="muted">São Paulo, SP</small></td>
                    <td><span class="badge-status bs-blue"><span class="b-dot"></span>Concluído</span></td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('eventos.show', 1) }}"><i class="bi bi-eye"></i>Ver detalhes</a></li>
                                <li><a class="dropdown-item" href="{{ route('inscritos.index') }}"><i class="bi bi-people"></i>Inscritos</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.edit', 1) }}"><i class="bi bi-pencil"></i>Editar</a></li>
                                <li><a class="dropdown-item" href="{{ route('eventos.create') }}"><i class="bi bi-files"></i>Duplicar</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash text-danger"></i>Excluir</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-foot d-flex align-items-center">
        <nav class="ms-auto" aria-label="Paginação de eventos">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-chevron-left"></i></a>
                </li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
