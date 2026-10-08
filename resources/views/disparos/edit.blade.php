@extends('layouts.app')

@section('title', 'Editar disparo')

@section('content')
    <div class="page-title d-flex align-items-start gap-3 flex-wrap">
        <div>
            <h2>Editar disparo</h2>
        </div>
        <div class="actions ms-auto d-flex flex-wrap">
            <a href="{{ route('disparos.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Disparos</a>
        </div>
    </div>

    <form method="POST" id="fDisparo">
        @csrf
        <div class="row g-3 align-items-start mb-4">

            <!-- Coluna esquerda: configuração -->
            <div class="col-lg-7 d-flex flex-column gap-3">

                <div class="card">
                    <div class="card-head">
                        <h3><span class="pill blue">1</span>&nbsp;Contatos</h3>
                    </div>
                    <div class="card-pad">
                        <div class="row g-2">
                            <div class="col-12 col-sm-6">
                                <label class="opt-card">
                                    <input type="radio" name="contatos" value="todos" class="visually-hidden">
                                    <div class="oc-ic"><i class="bi bi-people"></i></div>
                                    <b>Todos os contatos</b>
                                </label>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="opt-card on">
                                    <input type="radio" name="contatos" value="evento" class="visually-hidden" checked>
                                    <div class="oc-ic"><i class="bi bi-calendar-event"></i></div>
                                    <b>Inscritos em evento</b>
                                </label>
                            </div>
                        </div>
                        <div id="contatosEventos" class="mt-3">
                            <div class="search-inline w-100 mb-2">
                                <i class="bi bi-search"></i>
                                <input type="text" id="buscaEvento" placeholder="Buscar evento por nome...">
                            </div>
                            <div class="ev-pick">
                            <label class="ev-opt on">
                                <input type="checkbox" name="eventos[]" value="1" checked>
                                <div class="ev-name">Summit de Marketing Digital 2026</div>
                            </label>
                            <label class="ev-opt">
                                <input type="checkbox" name="eventos[]" value="2">
                                <div class="ev-name">Workshop de Vendas B2B — Turma 12</div>
                            </label>
                            <label class="ev-opt">
                                <input type="checkbox" name="eventos[]" value="3">
                                <div class="ev-name">Conferência de Produto &amp; Growth</div>
                            </label>
                            <label class="ev-opt">
                                <input type="checkbox" name="eventos[]" value="4">
                                <div class="ev-name">Bootcamp de Dados — Imersão 1 dia</div>
                            </label>
                            <label class="ev-opt">
                                <input type="checkbox" name="eventos[]" value="5">
                                <div class="ev-name">Meetup IA para Negócios</div>
                            </label>
                            </div>
                            <div class="form-hint">Seleciona os contatos inscritos nos eventos marcados. Marque um ou mais eventos.</div>
                        </div>
                        <div class="form-hint">"Todos os contatos" atinge toda a base de contatos, mesmo quem nunca se inscreveu em um evento.</div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-head">
                        <h3><span class="pill blue">2</span>&nbsp;Filtro</h3>
                        <div class="right"><span class="sub">escolha um</span></div>
                    </div>
                    <div class="card-pad">
                        <div class="row g-2">
                            <div class="col-12 col-sm-6">
                                <label class="opt-card">
                                    <input type="radio" name="filtro" value="sem_filtro" class="visually-hidden">
                                    <div class="oc-ic"><i class="bi bi-people"></i></div>
                                    <b>Sem filtro</b>
                                </label>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="opt-card">
                                    <input type="radio" name="filtro" value="pagantes" class="visually-hidden">
                                    <div class="oc-ic"><i class="bi bi-cash-coin"></i></div>
                                    <b>Pagantes</b>
                                </label>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="opt-card on">
                                    <input type="radio" name="filtro" value="nao_pagantes" class="visually-hidden" checked>
                                    <div class="oc-ic"><i class="bi bi-hourglass-split"></i></div>
                                    <b>Não pagantes</b>
                                </label>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="opt-card">
                                    <input type="radio" name="filtro" value="presentes" class="visually-hidden">
                                    <div class="oc-ic"><i class="bi bi-person-check"></i></div>
                                    <b>Presentes</b>
                                </label>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="opt-card">
                                    <input type="radio" name="filtro" value="ausentes" class="visually-hidden">
                                    <div class="oc-ic"><i class="bi bi-person-x"></i></div>
                                    <b>Ausentes</b>
                                </label>
                            </div>
                        </div>
                        <div class="form-hint">Estimativa de destinatários: <b>38</b></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-head">
                        <h3><span class="pill blue">3</span>&nbsp;Quando enviar</h3>
                    </div>
                    <div class="card-pad">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Quando</label>
                                <select class="form-select">
                                    <option>Agora</option>
                                    <option selected>Agendar data e hora</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Data</label>
                                <input class="form-control" type="date" value="2026-09-12">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hora</label>
                                <input class="form-control" type="time" value="10:00">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Coluna direita: mensagem -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-head">
                        <h3><span class="pill blue">4</span>&nbsp;Mensagem</h3>
                        <div class="right"><span class="sub">templates aprovados de WhatsApp</span></div>
                    </div>
                    <div class="card-pad">
                        <div class="field-group">
                            <label class="form-label">Template de WhatsApp</label>
                            <select class="form-select">
                                <option selected>Convite — lista quente · WhatsApp — Aprovado</option>
                                <option>Confirmação de inscrição · WhatsApp — Aprovado</option>
                                <option>No dia do evento · WhatsApp — Aprovado</option>
                                <option>Lembrete — 1 dia antes · WhatsApp — Aprovado</option>
                            </select>
                            <div class="form-hint">Só templates com status <b>Aprovado</b> na Meta aparecem aqui.</div>
                        </div>

                        <div class="field-group">
                            <label class="form-label">Conteúdo aprovado <span class="text-muted2 fw-normal">(somente leitura)</span></label>
                            <div class="tpl-body">Olá @{{1}}, abrimos um lote especial para quem já demonstrou interesse no @{{2}}.

Condição exclusiva por 48h: @{{3}}

Nos vemos em @{{4}}!</div>
                        </div>

                        <div class="field-group mb-0">
                            <label class="form-label">Vínculo das variáveis <span class="text-muted2 fw-normal">(@{{1}}, @{{2}}… → campos do sistema)</span></label>
                            <div class="border rounded-3 px-3 py-1">
                                <div class="vm-row d-flex align-items-center gap-2">
                                    <span class="mono flex-shrink-0">@{{1}}</span><i class="bi bi-arrow-right"></i>
                                    <select class="form-select flex-grow-1"><option selected>Nome do inscrito</option><option>Responsável</option><option>Nome do evento</option></select>
                                </div>
                                <div class="vm-row d-flex align-items-center gap-2">
                                    <span class="mono flex-shrink-0">@{{2}}</span><i class="bi bi-arrow-right"></i>
                                    <select class="form-select flex-grow-1"><option>Nome do inscrito</option><option selected>Nome do evento</option><option>Local</option></select>
                                </div>
                                <div class="vm-row d-flex align-items-center gap-2">
                                    <span class="mono flex-shrink-0">@{{3}}</span><i class="bi bi-arrow-right"></i>
                                    <select class="form-select flex-grow-1"><option selected>Link</option><option>Data do evento</option></select>
                                </div>
                                <div class="vm-row d-flex align-items-center gap-2">
                                    <span class="mono flex-shrink-0">@{{4}}</span><i class="bi bi-arrow-right"></i>
                                    <select class="form-select flex-grow-1"><option selected>Data do evento</option><option>Horário</option></select>
                                </div>
                            </div>
                            <div class="form-hint">Cada <span class="mono">@{{n}}</span> é preenchido com o valor do campo no momento do envio.</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="d-flex justify-content-between flex-wrap gap-2">
            <a href="{{ route('disparos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i>Salvar alterações</button>
        </div>
    </form>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/disparo.js') }}"></script>
@endpush
