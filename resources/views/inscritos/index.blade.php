@extends('layouts.app')

@section('title', 'Inscritos')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Inscritos</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="#" class="btn btn-outline-secondary"><i class="bi bi-filetype-csv"></i>Exportar CSV</a>
        <a href="{{ route('inscritos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i>Adicionar inscrito</a>
    </div>
</div>

<div class="card">
    <div class="toolbar d-flex flex-wrap align-items-center">
        <form method="GET" class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
            <div class="search-inline flex-grow-1">
                <i class="bi bi-search"></i>
                <input type="text" name="q" placeholder="Buscar por nome">
            </div>
            <select class="filter-select" name="evento">
                <option>Evento: Todos</option>
                <option selected>Summit de Marketing Digital 2026</option>
                <option>Workshop de Vendas B2B — Turma 12</option>
                <option>Conferência de Produto &amp; Growth</option>
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
                    <th>UTM</th>
                    <th>UTM Source</th>
                    <th>UTM Medium</th>
                    <th>UTM Campaign</th>
                    <th>UTM Content</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1 -->
                <tr>
                    <td>02/09/2026 08:14</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#2563eb">AS</span><b>Ana Silva</b></a></td>
                    <td>ana.silva@gmail.com</td>
                    <td>(11) 99012-3145</td>
                    <td class="mono">312.048.271-11</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Presente</span></td>
                    <td><span class="pill">VIP</span></td>
                    <td class="num">1</td>
                    <td class="num strong">R$ 1.200,00</td>
                    <td>CEO</td>
                    <td>Agência</td>
                    <td class="utm-cell mono">utm_source=instagram&amp;utm_medium=social&amp;utm_campaign=smd2026-lancamento</td>
                    <td>instagram</td><td>social</td><td>smd2026-lancamento</td><td>story-01</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 2 -->
                <tr>
                    <td>03/09/2026 09:27</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#0f766e">CS</span><b>Carlos Souza</b></a></td>
                    <td>carlos.souza@outlook.com</td>
                    <td>(11) 99133-2048</td>
                    <td class="mono">418.229.119-84</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Presente</span></td>
                    <td><span class="pill">Inteira</span></td>
                    <td class="num">1</td>
                    <td class="num strong">R$ 600,00</td>
                    <td>Diretor(a) de Marketing</td>
                    <td>SaaS B2B</td>
                    <td class="utm-cell mono">utm_source=google&amp;utm_medium=cpc&amp;utm_campaign=smd2026-lote2</td>
                    <td>google</td><td>cpc</td><td>smd2026-lote2</td><td>feed-carrossel</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 3 -->
                <tr>
                    <td>03/09/2026 14:02</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#7c3aed">JO</span><b>Juliana Oliveira</b></a></td>
                    <td>juliana.oliveira@empresa.com.br</td>
                    <td>(11) 99254-1927</td>
                    <td class="mono">526.410.777-57</td>
                    <td><span class="badge-status bs-amber"><span class="b-dot"></span>Pendente</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Ausente</span></td>
                    <td><span class="pill">Meia</span></td>
                    <td class="num">1</td>
                    <td class="num muted">—</td>
                    <td>Head de Growth</td>
                    <td>E-commerce</td>
                    <td class="utm-cell mono">utm_source=facebook&amp;utm_medium=organic&amp;utm_campaign=smd2026-remarketing</td>
                    <td>facebook</td><td>organic</td><td>smd2026-remarketing</td><td>bio-link</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 4 -->
                <tr>
                    <td>04/09/2026 10:41</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#db2777">PS</span><b>Pedro Santos</b></a></td>
                    <td>pedro.santos@hotmail.com</td>
                    <td>(11) 99375-0846</td>
                    <td class="mono">634.591.435-30</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Cortesia</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Presente</span></td>
                    <td><span class="pill">Cortesia</span></td>
                    <td class="num">2</td>
                    <td class="num muted">—</td>
                    <td>Analista de Marketing</td>
                    <td>Consultoria</td>
                    <td class="utm-cell mono">utm_source=linkedin&amp;utm_medium=referral&amp;utm_campaign=parceria-influ</td>
                    <td>linkedin</td><td>referral</td><td>parceria-influ</td><td>anuncio-video</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 5 -->
                <tr>
                    <td>04/09/2026 16:55</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#0891b2">FP</span><b>Fernanda Pereira</b></a></td>
                    <td>fernanda.pereira@gmail.com</td>
                    <td>(11) 99416-7290</td>
                    <td class="mono">742.680.817-16</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Gratuito</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Ausente</span></td>
                    <td><span class="pill">Estudante</span></td>
                    <td class="num">1</td>
                    <td class="num muted">—</td>
                    <td>Coordenador(a) de Vendas</td>
                    <td>Indústria</td>
                    <td class="utm-cell mono">utm_source=email&amp;utm_medium=email&amp;utm_campaign=lista-quente</td>
                    <td>email</td><td>email</td><td>lista-quente</td><td>newsletter-topo</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 6 -->
                <tr>
                    <td>05/09/2026 07:33</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#2563eb">LL</span><b>Lucas Lima</b></a></td>
                    <td>lucas.lima@empresa.com.br</td>
                    <td>(11) 99537-4183</td>
                    <td class="mono">851.749.140-72</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Presente</span></td>
                    <td><span class="pill">VIP</span></td>
                    <td class="num">1</td>
                    <td class="num strong">R$ 1.200,00</td>
                    <td>Sócio(a)</td>
                    <td>Educação</td>
                    <td class="utm-cell mono">utm_source=youtube&amp;utm_medium=paid&amp;utm_campaign=smd2026-lancamento</td>
                    <td>youtube</td><td>paid</td><td>smd2026-lancamento</td><td>cta-rodape</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 7 -->
                <tr>
                    <td>05/09/2026 11:18</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#ca8a04">MC</span><b>Mariana Carvalho</b></a></td>
                    <td>mariana.carvalho@outlook.com</td>
                    <td>(11) 99658-9037</td>
                    <td class="mono">960.318.963-45</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Presente</span></td>
                    <td><span class="pill">Inteira</span></td>
                    <td class="num">1</td>
                    <td class="num strong">R$ 600,00</td>
                    <td>CMO</td>
                    <td>Serviços Financeiros</td>
                    <td class="utm-cell mono">utm_source=direct&amp;utm_medium=organic&amp;utm_campaign=smd2026-lote2</td>
                    <td>direct</td><td>organic</td><td>smd2026-lote2</td><td>feed-carrossel</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 8 -->
                <tr>
                    <td>06/09/2026 09:02</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#334155">RR</span><b>Rodrigo Ribeiro</b></a></td>
                    <td>rodrigo.ribeiro@hotmail.com</td>
                    <td>(11) 99779-2184</td>
                    <td class="mono">178.926.012-33</td>
                    <td><span class="badge-status bs-amber"><span class="b-dot"></span>Pendente</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Ausente</span></td>
                    <td><span class="pill">Meia</span></td>
                    <td class="num">1</td>
                    <td class="num muted">—</td>
                    <td>Product Manager</td>
                    <td>Varejo</td>
                    <td class="utm-cell mono">utm_source=whatsapp&amp;utm_medium=referral&amp;utm_campaign=lista-quente</td>
                    <td>whatsapp</td><td>referral</td><td>lista-quente</td><td>bio-link</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 9 -->
                <tr>
                    <td>06/09/2026 13:44</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#be123c">PA</span><b>Patrícia Almeida</b></a></td>
                    <td>patricia.almeida@gmail.com</td>
                    <td>(11) 99810-4457</td>
                    <td class="mono">287.534.952-90</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Cortesia</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Ausente</span></td>
                    <td><span class="pill">Cortesia</span></td>
                    <td class="num">1</td>
                    <td class="num muted">—</td>
                    <td>Social Media</td>
                    <td>Infoproduto</td>
                    <td class="utm-cell mono">utm_source=instagram&amp;utm_medium=social&amp;utm_campaign=parceria-influ</td>
                    <td>instagram</td><td>social</td><td>parceria-influ</td><td>story-01</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 10 -->
                <tr>
                    <td>07/09/2026 08:20</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#15803d">TN</span><b>Thiago Nunes</b></a></td>
                    <td>thiago.nunes@empresa.com.br</td>
                    <td>(11) 99931-6620</td>
                    <td class="mono">396.148.910-27</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Presente</span></td>
                    <td><span class="pill">VIP</span></td>
                    <td class="num">1</td>
                    <td class="num strong">R$ 1.200,00</td>
                    <td>Consultor(a)</td>
                    <td>Startup</td>
                    <td class="utm-cell mono">utm_source=google&amp;utm_medium=cpc&amp;utm_campaign=smd2026-remarketing</td>
                    <td>google</td><td>cpc</td><td>smd2026-remarketing</td><td>anuncio-video</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 11 -->
                <tr>
                    <td>07/09/2026 15:37</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#2563eb">BG</span><b>Beatriz Gomes</b></a></td>
                    <td>beatriz.gomes@outlook.com</td>
                    <td>(11) 99042-8813</td>
                    <td class="mono">405.263.271-64</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Ausente</span></td>
                    <td><span class="pill">Inteira</span></td>
                    <td class="num">1</td>
                    <td class="num strong">R$ 600,00</td>
                    <td>Founder</td>
                    <td>Agência</td>
                    <td class="utm-cell mono">utm_source=facebook&amp;utm_medium=paid&amp;utm_campaign=smd2026-lote2</td>
                    <td>facebook</td><td>paid</td><td>smd2026-lote2</td><td>cta-rodape</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 12 -->
                <tr>
                    <td>08/09/2026 09:49</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#0891b2">GM</span><b>Gustavo Martins</b></a></td>
                    <td>gustavo.martins@hotmail.com</td>
                    <td>(11) 99163-0026</td>
                    <td class="mono">514.376.726-01</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Presente</span></td>
                    <td><span class="pill">Meia</span></td>
                    <td class="num">1</td>
                    <td class="num strong">R$ 600,00</td>
                    <td>Gerente Comercial</td>
                    <td>SaaS B2B</td>
                    <td class="utm-cell mono">utm_source=linkedin&amp;utm_medium=social&amp;utm_campaign=smd2026-lancamento</td>
                    <td>linkedin</td><td>social</td><td>smd2026-lancamento</td><td>feed-carrossel</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 13 -->
                <tr>
                    <td>09/09/2026 11:05</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#7c3aed">LR</span><b>Larissa Rocha</b></a></td>
                    <td>larissa.rocha@gmail.com</td>
                    <td>(11) 99284-5539</td>
                    <td class="mono">623.481.157-38</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Presente</span></td>
                    <td><span class="pill">VIP</span></td>
                    <td class="num">1</td>
                    <td class="num strong">R$ 1.200,00</td>
                    <td>CEO</td>
                    <td>Consultoria</td>
                    <td class="utm-cell mono">utm_source=instagram&amp;utm_medium=organic&amp;utm_campaign=smd2026-remarketing</td>
                    <td>instagram</td><td>organic</td><td>smd2026-remarketing</td><td>bio-link</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 14 -->
                <tr>
                    <td>10/09/2026 08:52</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#ca8a04">FB</span><b>Felipe Barbosa</b></a></td>
                    <td>felipe.barbosa@empresa.com.br</td>
                    <td>(11) 99305-1142</td>
                    <td class="mono">732.596.618-75</td>
                    <td><span class="badge-status bs-amber"><span class="b-dot"></span>Pendente</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Ausente</span></td>
                    <td><span class="pill">Inteira</span></td>
                    <td class="num">1</td>
                    <td class="num muted">—</td>
                    <td>Diretor(a) de Marketing</td>
                    <td>Indústria</td>
                    <td class="utm-cell mono">utm_source=google&amp;utm_medium=cpc&amp;utm_campaign=lista-quente</td>
                    <td>google</td><td>cpc</td><td>lista-quente</td><td>anuncio-video</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 15 -->
                <tr>
                    <td>11/09/2026 14:16</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#0f766e">RT</span><b>Renata Teixeira</b></a></td>
                    <td>renata.teixeira@outlook.com</td>
                    <td>(11) 99426-8848</td>
                    <td class="mono">841.703.055-12</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Gratuito</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Presente</span></td>
                    <td><span class="pill">Estudante</span></td>
                    <td class="num">1</td>
                    <td class="num muted">—</td>
                    <td>Analista de Marketing</td>
                    <td>Educação</td>
                    <td class="utm-cell mono">utm_source=email&amp;utm_medium=email&amp;utm_campaign=smd2026-lancamento</td>
                    <td>email</td><td>email</td><td>smd2026-lancamento</td><td>newsletter-topo</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 16 -->
                <tr>
                    <td>12/09/2026 10:31</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#334155">DC</span><b>Diego Cardoso</b></a></td>
                    <td>diego.cardoso@hotmail.com</td>
                    <td>(11) 99547-3351</td>
                    <td class="mono">950.816.510-49</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Ausente</span></td>
                    <td><span class="pill">VIP</span></td>
                    <td class="num">2</td>
                    <td class="num strong">R$ 1.200,00</td>
                    <td>Sócio(a)</td>
                    <td>Serviços Financeiros</td>
                    <td class="utm-cell mono">utm_source=direct&amp;utm_medium=organic&amp;utm_campaign=smd2026-remarketing</td>
                    <td>direct</td><td>organic</td><td>smd2026-remarketing</td><td>cta-rodape</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 17 -->
                <tr>
                    <td>13/09/2026 09:07</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#db2777">AM</span><b>Aline Moraes</b></a></td>
                    <td>aline.moraes@gmail.com</td>
                    <td>(11) 99668-9954</td>
                    <td class="mono">169.238.002-86</td>
                    <td><span class="badge-status bs-amber"><span class="b-dot"></span>Pendente</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Ausente</span></td>
                    <td><span class="pill">Inteira</span></td>
                    <td class="num">1</td>
                    <td class="num muted">—</td>
                    <td>Coordenador(a) de Vendas</td>
                    <td>Varejo</td>
                    <td class="utm-cell mono">utm_source=whatsapp&amp;utm_medium=referral&amp;utm_campaign=parceria-influ</td>
                    <td>whatsapp</td><td>referral</td><td>parceria-influ</td><td>bio-link</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 18 -->
                <tr>
                    <td>14/09/2026 16:48</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#15803d">MF</span><b>Marcelo Freitas</b></a></td>
                    <td>marcelo.freitas@empresa.com.br</td>
                    <td>(11) 99789-1160</td>
                    <td class="mono">278.951.257-73</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Presente</span></td>
                    <td><span class="pill">Meia</span></td>
                    <td class="num">1</td>
                    <td class="num strong">R$ 600,00</td>
                    <td>CMO</td>
                    <td>Infoproduto</td>
                    <td class="utm-cell mono">utm_source=youtube&amp;utm_medium=paid&amp;utm_campaign=smd2026-lote2</td>
                    <td>youtube</td><td>paid</td><td>smd2026-lote2</td><td>anuncio-video</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 19 -->
                <tr>
                    <td>15/09/2026 08:29</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#2563eb">VA</span><b>Vanessa Araújo</b></a></td>
                    <td>vanessa.araujo@outlook.com</td>
                    <td>(11) 99810-2267</td>
                    <td class="mono">387.164.012-22</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-green"><span class="b-dot"></span>Presente</span></td>
                    <td><span class="pill">VIP</span></td>
                    <td class="num">1</td>
                    <td class="num strong">R$ 1.200,00</td>
                    <td>Founder</td>
                    <td>Startup</td>
                    <td class="utm-cell mono">utm_source=instagram&amp;utm_medium=social&amp;utm_campaign=smd2026-lancamento</td>
                    <td>instagram</td><td>social</td><td>smd2026-lancamento</td><td>story-01</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <!-- 20 -->
                <tr>
                    <td>16/09/2026 12:10</td>
                    <td><a href="{{ route('inscritos.show', 1) }}" class="u-cell text-reset"><span class="avatar" style="background:#ca8a04">RP</span><b>Rafael Pinto</b></a></td>
                    <td>rafael.pinto@hotmail.com</td>
                    <td>(11) 99931-5573</td>
                    <td class="mono">496.278.470-95</td>
                    <td><span class="badge-status bs-amber"><span class="b-dot"></span>Pendente</span></td>
                    <td>Summit de Marketing Digital 2026</td>
                    <td><span class="badge-status bs-slate"><span class="b-dot"></span>Ausente</span></td>
                    <td><span class="pill">Inteira</span></td>
                    <td class="num">1</td>
                    <td class="num muted">—</td>
                    <td>Gerente Comercial</td>
                    <td>Agência</td>
                    <td class="utm-cell mono">utm_source=google&amp;utm_medium=cpc&amp;utm_campaign=smd2026-lote2</td>
                    <td>google</td><td>cpc</td><td>smd2026-lote2</td><td>feed-carrossel</td>
                    <td class="text-end">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('inscritos.show', 1) }}">
                                        <i class="bi bi-eye"></i>Detalhes
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#">
                                        <i class="bi bi-trash text-danger"></i>Excluir
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-foot d-flex align-items-center flex-wrap gap-2">
        Mostrando <b>1–20</b> de <b>128</b> inscritos
        <select class="filter-select ms-2" style="height:32px">
            <option>20 por página</option>
            <option>50 por página</option>
            <option>100 por página</option>
        </select>
        <nav class="ms-auto" aria-label="Paginação de inscritos">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-chevron-left"></i></a>
                </li>
                <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item disabled"><a class="page-link" href="#">…</a></li>
                <li class="page-item"><a class="page-link" href="#">7</a></li>
                <li class="page-item">
                    <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    </div>
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
                                <input type="text" class="form-control" name="q" placeholder="Buscar por nome">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Evento</label>
                            <select class="form-select" name="evento">
                                <option>Todos</option>
                                <option selected>Summit de Marketing Digital 2026</option>
                                <option>Workshop de Vendas B2B — Turma 12</option>
                                <option>Conferência de Produto &amp; Growth</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">CPF</label>
                            <div class="input-icon">
                                <i class="bi bi-person-vcard"></i>
                                <input type="text" class="form-control" name="cpf" placeholder="000.000.000-00" inputmode="numeric">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Pagamento</label>
                            <select class="form-select" name="pagamento">
                                <option>Todos</option>
                                <option>Pago</option>
                                <option>Pendente</option>
                                <option>Gratuito / Cortesia</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Presença</label>
                            <select class="form-select" name="presenca">
                                <option>Todos</option>
                                <option>Presente</option>
                                <option>Ausente</option>
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
