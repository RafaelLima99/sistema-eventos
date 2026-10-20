@extends('layouts.app')

@section('title', 'Contatos')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Contatos</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalImportarContatos"><i class="bi bi-upload"></i>Importar CSV</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNovoContato"><i class="bi bi-person-plus"></i>Novo contato</button>
    </div>
</div>

<div class="card">
    <div class="toolbar d-flex align-items-center flex-wrap">
        <form method="GET" class="d-flex align-items-center flex-wrap flex-grow-1 gap-2">
            <div class="search-inline position-relative flex-grow-1">
                <i class="bi bi-search position-absolute"></i>
                <input type="text" name="busca_nome" value="{{ request('busca_nome') }}" class="w-100" placeholder="Busca por nome">
            </div>
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i>Buscar</button>
        </form>
    </div>

    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th>Contato</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Cargo</th>
                    <th>Negócio</th>
                    <th>Cadastrado em</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contatos as $contato)
                    <tr>
                        <td>
                            <div class="u-cell d-flex align-items-center">
                                <span class="avatar avatar-cor-{{ $contato->id % 6 }}">{{ $contato->iniciaisDoNome() }}</span>
                                <b>{{ $contato->nome }}</b>
                            </div>
                        </td>
                        <td>{{ $contato->email ?? '—' }}</td>
                        <td>{{ $contato->telefone ?? '—' }}</td>
                        <td>{{ $contato->cargo ?? '—' }}</td>
                        <td>{{ $contato->negocio ?? '—' }}</td>
                        <td class="muted">{{ $contato->created_at->format('d/m/Y') }}</td>
                        <td class="text-end"><a href="{{ route('contatos.show', $contato) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Nenhum contato encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($contatos->hasPages())
        <div class="table-foot d-flex align-items-center">
            {{ $contatos->links() }}
        </div>
    @endif
</div>

<!-- Modal: Novo contato -->
<div class="modal fade" id="modalNovoContato" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Novo contato</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form method="POST" action="{{ route('contatos.store') }}" id="fNovoContato">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nome completo</label>
                            <input name="nome" value="{{ old('nome') }}" class="form-control @error('nome') is-invalid @enderror" placeholder="Ex.: Ana Beatriz Silva">
                            @error('nome')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail</label>
                            <input name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" type="email" placeholder="nome@empresa.com">
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone</label>
                            <input name="telefone" value="{{ old('telefone') }}" class="form-control @error('telefone') is-invalid @enderror" placeholder="(11) 99000-0000">
                            @error('telefone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CPF</label>
                            <input name="cpf" value="{{ old('cpf') }}" class="form-control mono @error('cpf') is-invalid @enderror" placeholder="000.000.000-00">
                            @error('cpf')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cargo</label>
                            <input name="cargo" value="{{ old('cargo') }}" class="form-control @error('cargo') is-invalid @enderror" placeholder="Ex.: Diretor(a) de Marketing">
                            @error('cargo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Negócio</label>
                            <input name="negocio" value="{{ old('negocio') }}" class="form-control @error('negocio') is-invalid @enderror" placeholder="Ex.: Agência, SaaS B2B...">
                            @error('negocio')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="fNovoContato" class="btn btn-primary"><i class="bi bi-check-lg"></i>Salvar contato</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Importar CSV -->
<div class="modal fade" id="modalImportarContatos" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Importar contatos (CSV)</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form method="POST" action="{{ route('contatos.importacao.store') }}" enctype="multipart/form-data" id="fImportarCsv">
                    @csrf
                    {{-- Vincular a um evento: será implementado junto com o CRUD de inscritos.
                    <div class="mb-3">
                        <label class="form-label">Vincular a um evento <span class="text-muted2 fw-normal">(opcional)</span></label>
                        <select class="form-select">
                            <option selected>Não vincular — só adicionar à base de contatos</option>
                            <option>Summit de Marketing Digital 2026</option>
                            <option>Workshop de Vendas B2B — Turma 12</option>
                            <option>Conferência de Produto &amp; Growth</option>
                            <option>Bootcamp de Dados — Imersão 1 dia</option>
                            <option>Meetup IA para Negócios</option>
                        </select>
                    </div>
                    --}}
                    <div class="mb-3">
                        <label class="form-label">Arquivo CSV</label>
                        <input type="file" name="arquivo" class="form-control @error('arquivo', 'importacao') is-invalid @enderror" accept=".csv">
                        @error('arquivo', 'importacao')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-hint">Colunas suportadas: nome, email, whatsapp, cpf, cargo, negocio</div>
                    </div>
                </form>
                <a href="{{ asset('assets/modelos/contatos.csv') }}" download class="d-inline-block" style="font-size:12.5px;margin-top:10px"><i class="bi bi-download"></i> Baixar modelo de planilha</a>
                <div class="note-box" style="margin-top:14px">
                    <i class="bi bi-info-circle flex-shrink-0"></i>
                    <span>Contatos com e-mail ou CPF já existentes são atualizados, não duplicados.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="fImportarCsv" class="btn btn-primary"><i class="bi bi-upload"></i>Importar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@if ($errors->any())
    @push('scripts')
    <script>
        // Reabre o modal de novo contato para mostrar os erros de validação.
        new bootstrap.Modal(document.getElementById('modalNovoContato')).show();
    </script>
    @endpush
@elseif ($errors->importacao->any())
    @push('scripts')
    <script>
        // Reabre o modal de importação para mostrar os erros de validação.
        new bootstrap.Modal(document.getElementById('modalImportarContatos')).show();
    </script>
    @endpush
@endif
