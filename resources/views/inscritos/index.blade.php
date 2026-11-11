@extends('layouts.app')

@section('title', 'Inscritos')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Inscritos</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('inscritos.exportacao.index', request()->except('page')) }}" class="btn btn-outline-secondary"><i class="bi bi-filetype-csv"></i>Exportar CSV</a>
        <a href="{{ route('inscritos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i>Adicionar inscrito</a>
    </div>
</div>

<div class="card">
    <div class="toolbar d-flex flex-wrap align-items-center">
        <form method="GET" class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
            <div class="search-inline flex-grow-1">
                <i class="bi bi-search"></i>
                <input type="text" name="busca_nome" value="{{ request('busca_nome') }}" placeholder="Buscar por nome">
            </div>
            <select class="filter-select" name="evento">
                <option value="">Evento: Todos</option>
                @foreach ($eventos as $evento)
                    <option value="{{ $evento->id }}" @selected(request('evento') == $evento->id)>{{ $evento->titulo }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#mFiltroDetalhado"><i class="bi bi-sliders"></i>Busca detalhada</button>
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i>Buscar</button>
        </form>
    </div>

    <div class="table-wrap wide-table mt-2">
        <table class="data">
            <thead>
                <tr>
                    <th>Data de Inscrição</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th>Status Ingresso</th>
                    <th>Evento</th>
                    <th>Presente</th>
                    <th>Tipo de Ingresso</th>
                    <th>Quantidade de Ingresso</th>
                    <th>Valor Ingresso</th>
                    <th>Cargo</th>
                    <th>Negócio</th>
                    <th>UTM Source</th>
                    <th>UTM Medium</th>
                    <th>UTM Campaign</th>
                    <th>UTM Content</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inscritos as $inscrito)
                    @php($contato = $inscrito->contato)
                    <tr>
                        <td>{{ $inscrito->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('inscritos.show', $inscrito->id) }}" class="u-cell text-reset">
                                <span class="avatar avatar-cor-{{ $contato->id % 6 }}">{{ $contato->iniciaisDoNome() }}</span>
                                <b>{{ $contato->nome }}</b>
                            </a>
                        </td>
                        <td>{{ $contato->email ?? '—' }}</td>
                        <td>{{ $contato->telefone ?? '—' }}</td>
                        <td class="mono">{{ $contato->cpfFormatado() ?? '—' }}</td>
                        <td><span class="badge-status {{ $inscrito->status_ingresso->badgeClass() }}"><span class="b-dot"></span>{{ $inscrito->status_ingresso->label() }}</span></td>
                        <td>{{ $inscrito->evento->titulo }}</td>
                        <td><span class="badge-status {{ $inscrito->status_presenca->badgeClass() }}"><span class="b-dot"></span>{{ $inscrito->status_presenca->label() }}</span></td>
                        <td>
                            @if ($inscrito->tipo_ingresso)
                                <span class="pill">{{ $inscrito->tipo_ingresso }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td class="num">{{ $inscrito->quantidade_ingresso ?? '—' }}</td>
                        @if ($inscrito->valor_pago !== null)
                            <td class="num strong">R$ {{ number_format($inscrito->valor_pago, 2, ',', '.') }}</td>
                        @else
                            <td class="num muted">—</td>
                        @endif
                        <td>{{ $contato->cargo ?? '—' }}</td>
                        <td>{{ $contato->negocio ?? '—' }}</td>
                        <td>{{ $inscrito->utm_source ?? '—' }}</td>
                        <td>{{ $inscrito->utm_medium ?? '—' }}</td>
                        <td>{{ $inscrito->utm_campaign ?? '—' }}</td>
                        <td>{{ $inscrito->utm_content ?? '—' }}</td>
                        <td class="text-end">
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown" data-bs-config='{"popperConfig":{"strategy":"fixed"}}'>
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('inscritos.show', $inscrito->id) }}">
                                            <i class="bi bi-eye"></i>Detalhes
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('inscritos.destroy', $inscrito) }}" onsubmit="return confirm(@js('Deseja excluir a inscrição de "'.$contato->nome.'"?'))">
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
                        <td colspan="18" class="text-center text-muted py-4">Nenhum inscrito encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($inscritos->hasPages())
        <div class="table-foot d-flex align-items-center">
            {{ $inscritos->links() }}
        </div>
    @endif
</div>

<!-- Modal: Busca detalhada -->
<div class="modal fade" id="mFiltroDetalhado" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Busca detalhada</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="GET" id="fBuscaDetalhada">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nome</label>
                            <div class="input-icon">
                                <i class="bi bi-search"></i>
                                <input type="text" class="form-control" name="busca_nome" value="{{ request('busca_nome') }}" placeholder="Buscar por nome">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Evento</label>
                            <select class="form-select" name="evento">
                                <option value="">Todos</option>
                                @foreach ($eventos as $evento)
                                    <option value="{{ $evento->id }}" @selected(request('evento') == $evento->id)>{{ $evento->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">CPF</label>
                            <div class="input-icon">
                                <i class="bi bi-person-vcard"></i>
                                <input type="text" class="form-control" name="cpf" data-mascara-cpf value="{{ request('cpf') }}" placeholder="000.000.000-00" inputmode="numeric" maxlength="14">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Status do ingresso</label>
                            <select class="form-select" name="status_ingresso">
                                <option value="">Todos</option>
                                @foreach (\App\Enums\StatusIngresso::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(request('status_ingresso') === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Presença</label>
                            <select class="form-select" name="status_presenca">
                                <option value="">Todos</option>
                                @foreach (\App\Enums\StatusPresenca::cases() as $status)
                                    <option value="{{ $status->value }}" @selected(request('status_presenca') === $status->value)>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="fBuscaDetalhada" class="btn btn-primary"><i class="bi bi-search"></i>Buscar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/mascara-cpf.js') }}"></script>
@endpush
