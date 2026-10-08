@extends('layouts.app')

@section('title', 'Contatos')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Contatos</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#mImport"><i class="bi bi-upload"></i>Importar CSV</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mNovo"><i class="bi bi-person-plus"></i>Novo contato</button>
    </div>
</div>

<div class="card">
    <div class="toolbar d-flex align-items-center flex-wrap">
        <form method="GET" class="d-flex align-items-center flex-wrap flex-grow-1 gap-2">
            <div class="search-inline position-relative flex-grow-1">
                <i class="bi bi-search position-absolute"></i>
                <input type="text" name="q" class="w-100" placeholder="Busca por nome">
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
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#2563eb">AS</span><b>Ana Silva</b></div></td>
                    <td>ana.silva@gmail.com</td>
                    <td>(11) 99012-3145</td>
                    <td>CEO</td>
                    <td>Agência</td>
                    <td class="muted">02/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#0f766e">CS</span><b>Carlos Souza</b></div></td>
                    <td>carlos.souza@outlook.com</td>
                    <td>(11) 99133-2048</td>
                    <td>Diretor(a) de Marketing</td>
                    <td>SaaS B2B</td>
                    <td class="muted">03/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#7c3aed">JO</span><b>Juliana Oliveira</b></div></td>
                    <td>juliana.oliveira@empresa.com.br</td>
                    <td>(11) 99254-1927</td>
                    <td>Head de Growth</td>
                    <td>E-commerce</td>
                    <td class="muted">03/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#db2777">PS</span><b>Pedro Santos</b></div></td>
                    <td>pedro.santos@hotmail.com</td>
                    <td>(11) 99375-0846</td>
                    <td>Analista de Marketing</td>
                    <td>Consultoria</td>
                    <td class="muted">04/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#2563eb">LL</span><b>Lucas Lima</b></div></td>
                    <td>lucas.lima@empresa.com.br</td>
                    <td>(11) 99537-4183</td>
                    <td>Sócio(a)</td>
                    <td>Educação</td>
                    <td class="muted">05/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#ca8a04">MC</span><b>Mariana Carvalho</b></div></td>
                    <td>mariana.carvalho@outlook.com</td>
                    <td>(11) 99658-9037</td>
                    <td>CMO</td>
                    <td>Serviços Financeiros</td>
                    <td class="muted">05/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#334155">RR</span><b>Rodrigo Ribeiro</b></div></td>
                    <td>rodrigo.ribeiro@hotmail.com</td>
                    <td>(11) 99779-2184</td>
                    <td>Product Manager</td>
                    <td>Varejo</td>
                    <td class="muted">06/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#15803d">TN</span><b>Thiago Nunes</b></div></td>
                    <td>thiago.nunes@empresa.com.br</td>
                    <td>(11) 99931-6620</td>
                    <td>Consultor(a)</td>
                    <td>Startup</td>
                    <td class="muted">06/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#2563eb">BG</span><b>Beatriz Gomes</b></div></td>
                    <td>beatriz.gomes@outlook.com</td>
                    <td>(11) 99042-8813</td>
                    <td>Founder</td>
                    <td>Agência</td>
                    <td class="muted">07/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#0891b2">GM</span><b>Gustavo Martins</b></div></td>
                    <td>gustavo.martins@hotmail.com</td>
                    <td>(11) 99163-0026</td>
                    <td>Gerente Comercial</td>
                    <td>SaaS B2B</td>
                    <td class="muted">08/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#7c3aed">LR</span><b>Larissa Rocha</b></div></td>
                    <td>larissa.rocha@gmail.com</td>
                    <td>(11) 99284-5539</td>
                    <td>CEO</td>
                    <td>Consultoria</td>
                    <td class="muted">09/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#ca8a04">FB</span><b>Felipe Barbosa</b></div></td>
                    <td>felipe.barbosa@empresa.com.br</td>
                    <td>(11) 99305-1142</td>
                    <td>Diretor(a) de Marketing</td>
                    <td>Indústria</td>
                    <td class="muted">10/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#0f766e">RT</span><b>Renata Teixeira</b></div></td>
                    <td>renata.teixeira@outlook.com</td>
                    <td>(11) 99426-8848</td>
                    <td>Analista de Marketing</td>
                    <td>Educação</td>
                    <td class="muted">14/08/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#334155">DC</span><b>Diego Cardoso</b></div></td>
                    <td>diego.cardoso@hotmail.com</td>
                    <td>(11) 99547-3351</td>
                    <td>Sócio(a)</td>
                    <td>Serviços Financeiros</td>
                    <td class="muted">25/08/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#db2777">AM</span><b>Aline Moraes</b></div></td>
                    <td>aline.moraes@gmail.com</td>
                    <td>(11) 99668-9954</td>
                    <td>Coordenador(a) de Vendas</td>
                    <td>Varejo</td>
                    <td class="muted">30/08/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
                <tr>
                    <td><div class="u-cell d-flex align-items-center"><span class="avatar" style="background:#15803d">MF</span><b>Marcelo Freitas</b></div></td>
                    <td>marcelo.freitas@empresa.com.br</td>
                    <td>(11) 99789-1160</td>
                    <td>CMO</td>
                    <td>Infoproduto</td>
                    <td class="muted">01/09/2026</td>
                    <td class="text-end"><a href="{{ route('contatos.show', 1) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="table-foot d-flex align-items-center">
        <nav class="ms-auto" aria-label="Paginação de contatos">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-chevron-left"></i></a>
                </li>
                <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">…</a></li>
                <li class="page-item"><a class="page-link" href="#">20</a></li>
                <li class="page-item">
                    <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<!-- Modal: Novo contato -->
<div class="modal fade" id="mNovo" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Novo contato</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form method="POST" id="fNovoContato">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12"><label class="form-label">Nome completo</label><input class="form-control" placeholder="Ex.: Ana Beatriz Silva"></div>
                        <div class="col-md-6"><label class="form-label">E-mail</label><input class="form-control" type="email" placeholder="nome@empresa.com"></div>
                        <div class="col-md-6"><label class="form-label">Telefone</label><input class="form-control" placeholder="(11) 99000-0000"></div>
                        <div class="col-md-6"><label class="form-label">CPF</label><input class="form-control mono" placeholder="000.000.000-00"></div>
                        <div class="col-md-6"><label class="form-label">Cargo</label><input class="form-control" placeholder="Ex.: Diretor(a) de Marketing"></div>
                        <div class="col-md-6"><label class="form-label">Negócio</label><input class="form-control" placeholder="Ex.: Agência, SaaS B2B..."></div>
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
<div class="modal fade" id="mImport" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Importar contatos (CSV)</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="fImportarCsv">
                    @csrf
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
                    <div class="mb-3">
                        <label class="form-label">Arquivo CSV</label>
                        <input type="file" class="form-control" accept=".csv">
                        <div class="form-hint">Colunas suportadas: nome, email, whatsapp, cpf, cargo, negocio, cidade</div>
                    </div>
                </form>
                <a href="#" class="d-inline-block" style="font-size:12.5px;margin-top:10px"><i class="bi bi-download"></i> Baixar modelo de planilha</a>
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
