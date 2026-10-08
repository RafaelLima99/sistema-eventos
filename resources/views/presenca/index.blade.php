@extends('layouts.app')

@section('title', 'Controle de presença')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Credenciamento — Summit de Marketing Digital 2026</h2>
    </div>
</div>

<div class="card">
    <div class="toolbar">
        <form method="GET" class="row g-2 flex-grow-1 align-items-center">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="search-inline">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Busca por nome">
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="search-inline">
                    <i class="bi bi-person-vcard"></i>
                    <input type="text" name="cpf" placeholder="Busca por CPF" inputmode="numeric">
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl">
                <select class="filter-select w-100" name="evento">
                    <option selected>Summit de Marketing Digital 2026</option>
                    <option>Workshop de Vendas B2B — Turma 12</option>
                    <option>Conferência de Produto &amp; Growth</option>
                </select>
            </div>
            <div class="col-12 col-md-6 col-xl-auto d-grid d-xl-block">
                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i>Buscar</button>
            </div>
        </form>
    </div>

    <div>
        <div class="p-row checked px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#2563eb">AS</span>
                    <div class="who">
                        <b class="d-block">Ana Silva</b>
                        <small>ana.silva@gmail.com · (11) 99012-3145 · CPF 312.048.271-11</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>1 ingresso</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-check-lg"></i>Presente</button></div>
            </div>
        </div>
        <div class="p-row checked px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#0f766e">CS</span>
                    <div class="who">
                        <b class="d-block">Carlos Souza</b>
                        <small>carlos.souza@outlook.com · (11) 99133-2048 · CPF 418.229.119-84</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>1 ingresso</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-check-lg"></i>Presente</button></div>
            </div>
        </div>
        <div class="p-row px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#7c3aed">JO</span>
                    <div class="who">
                        <b class="d-block">Juliana Oliveira</b>
                        <small>juliana.oliveira@empresa.com.br · (11) 99254-1927 · CPF 526.410.777-57</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>1 ingresso</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-primary btn-sm"><i class="bi bi-check-lg"></i>Confirmar Presença</button></div>
            </div>
        </div>
        <div class="p-row px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#db2777">PS</span>
                    <div class="who">
                        <b class="d-block">Pedro Santos</b>
                        <small>pedro.santos@hotmail.com · (11) 99375-0846 · CPF 634.591.435-30</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>2 ingressos</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-primary btn-sm"><i class="bi bi-check-lg"></i>Confirmar Presença</button></div>
            </div>
        </div>
        <div class="p-row checked px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#2563eb">LL</span>
                    <div class="who">
                        <b class="d-block">Lucas Lima</b>
                        <small>lucas.lima@empresa.com.br · (11) 99537-4183 · CPF 851.749.140-72</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>1 ingresso</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-check-lg"></i>Presente</button></div>
            </div>
        </div>
        <div class="p-row px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#ca8a04">MC</span>
                    <div class="who">
                        <b class="d-block">Mariana Carvalho</b>
                        <small>mariana.carvalho@outlook.com · (11) 99658-9037 · CPF 960.318.963-45</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>1 ingresso</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-primary btn-sm"><i class="bi bi-check-lg"></i>Confirmar Presença</button></div>
            </div>
        </div>
        <div class="p-row px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#334155">RR</span>
                    <div class="who">
                        <b class="d-block">Rodrigo Ribeiro</b>
                        <small>rodrigo.ribeiro@hotmail.com · (11) 99779-2184 · CPF 178.926.012-33</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>1 ingresso</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-primary btn-sm"><i class="bi bi-check-lg"></i>Confirmar Presença</button></div>
            </div>
        </div>
        <div class="p-row checked px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#15803d">TN</span>
                    <div class="who">
                        <b class="d-block">Thiago Nunes</b>
                        <small>thiago.nunes@empresa.com.br · (11) 99931-6620 · CPF 396.148.910-27</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>1 ingresso</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-check-lg"></i>Presente</button></div>
            </div>
        </div>
        <div class="p-row checked px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#2563eb">BG</span>
                    <div class="who">
                        <b class="d-block">Beatriz Gomes</b>
                        <small>beatriz.gomes@outlook.com · (11) 99042-8813 · CPF 405.263.271-64</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>1 ingresso</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-check-lg"></i>Presente</button></div>
            </div>
        </div>
        <div class="p-row px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#0891b2">GM</span>
                    <div class="who">
                        <b class="d-block">Gustavo Martins</b>
                        <small>gustavo.martins@hotmail.com · (11) 99163-0026 · CPF 514.376.726-01</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>1 ingresso</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-primary btn-sm"><i class="bi bi-check-lg"></i>Confirmar Presença</button></div>
            </div>
        </div>
        <div class="p-row px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#7c3aed">LR</span>
                    <div class="who">
                        <b class="d-block">Larissa Rocha</b>
                        <small>larissa.rocha@gmail.com · (11) 99284-5539 · CPF 623.481.157-38</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>1 ingresso</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-primary btn-sm"><i class="bi bi-check-lg"></i>Confirmar Presença</button></div>
            </div>
        </div>
        <div class="p-row px-3 py-2">
            <div class="row gx-3 gy-2 align-items-center">
                <div class="col-12 col-md d-flex align-items-center gap-2">
                    <span class="avatar" style="background:#ca8a04">FB</span>
                    <div class="who">
                        <b class="d-block">Felipe Barbosa</b>
                        <small>felipe.barbosa@empresa.com.br · (11) 99305-1142 · CPF 732.596.618-75</small>
                    </div>
                </div>
                <div class="col-6 col-md-auto"><span class="pill"><i class="bi bi-ticket-perforated"></i>2 ingressos</span></div>
                <div class="col-6 col-md-auto text-end"><button class="btn btn-primary btn-sm"><i class="bi bi-check-lg"></i>Confirmar Presença</button></div>
            </div>
        </div>
    </div>

    <div class="table-foot d-flex align-items-center">
        <nav class="ms-auto" aria-label="Paginação de participantes">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="bi bi-chevron-left"></i></a>
                </li>
                <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
