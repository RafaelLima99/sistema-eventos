@extends('layouts.app')

@section('title', 'Nova mensagem de WhatsApp')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Nova mensagem de WhatsApp</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('whatsapp-mensagens.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Mensagens</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-pad">
        <form method="POST" id="fNovaMensagemWhatsapp">
            @csrf
            <div class="row g-3">

                <div>
                    <div class="note-box">
                        <i class="bi bi-info-circle flex-shrink-0"></i>
                        <span>Templates de WhatsApp são criados e aprovados na <b>Meta (WhatsApp Manager)</b>. Aqui você escolhe um template <b>aprovado</b>, dá um nome interno e faz o vínculo das variáveis.</span>
                    </div>
                </div>

                <div>
                    <label class="form-label">Nome no sistema</label>
                    <input class="form-control" value="Confirmação de inscrição · WhatsApp">
                </div>

                <div>
                    <label class="form-label">Template aprovado</label>
                    <div class="search-inline mb-2">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Buscar template...">
                    </div>
                    <div class="tpl-pick">
                        <label class="tpl-opt on">
                            <input type="radio" name="tpl" checked>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="tp-name mono">confirmacao_inscricao</span>
                                    <span class="cat util">Utilidade</span>
                                    <span class="badge-status bs-green"><span class="b-dot"></span>Aprovado</span>
                                </div>
                                <div class="tp-meta">pt_BR · 5 variáveis · qualidade alta</div>
                                <div class="tp-body">Olá @{{1}}! 🎉 Sua inscrição no *@{{2}}* foi confirmada. 📅 @{{3}} às @{{4}} 📍 @{{5}}</div>
                            </div>
                        </label>
                        <label class="tpl-opt">
                            <input type="radio" name="tpl">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="tp-name mono">lembrete_pagamento_15min</span>
                                    <span class="cat util">Utilidade</span>
                                    <span class="badge-status bs-green"><span class="b-dot"></span>Aprovado</span>
                                </div>
                                <div class="tp-meta">pt_BR · 3 variáveis · qualidade alta</div>
                                <div class="tp-body">Olá @{{1}}! Sua vaga no @{{2}} está reservada, mas o pagamento não foi concluído. Finalize: @{{3}}</div>
                            </div>
                        </label>
                        <label class="tpl-opt">
                            <input type="radio" name="tpl">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="tp-name mono">lembrete_vespera</span>
                                    <span class="cat util">Utilidade</span>
                                    <span class="badge-status bs-green"><span class="b-dot"></span>Aprovado</span>
                                </div>
                                <div class="tp-meta">pt_BR · 4 variáveis · qualidade média</div>
                                <div class="tp-body">Amanhã é o dia, @{{1}}! @{{2}} — @{{3}} às @{{4}}. Chegue 30 min antes.</div>
                            </div>
                        </label>
                        <label class="tpl-opt">
                            <input type="radio" name="tpl">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="tp-name mono">aviso_dia_evento</span>
                                    <span class="cat util">Utilidade</span>
                                    <span class="badge-status bs-green"><span class="b-dot"></span>Aprovado</span>
                                </div>
                                <div class="tp-meta">pt_BR · 3 variáveis · qualidade alta</div>
                                <div class="tp-body">Bom dia, @{{1}}! Hoje tem @{{2}}. Início às @{{3}}. Seu QR Code no link.</div>
                            </div>
                        </label>
                        <label class="tpl-opt disabled">
                            <input type="radio" name="tpl" disabled>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="tp-name mono">convite_lista_quente</span>
                                    <span class="cat mkt">Marketing</span>
                                    <span class="badge-status bs-amber"><span class="b-dot"></span>Em análise</span>
                                </div>
                                <div class="tp-meta">pt_BR · 4 variáveis · aguardando aprovação da Meta · Marketing exige opt-in</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="form-label">Vínculo das variáveis <span class="text-muted2 fw-normal">(@{{1}}, @{{2}}… → campos do sistema)</span></label>
                    <div class="border rounded-3 px-3 py-1">
                        <div class="vm-row">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto mono">@{{1}}</div>
                                <div class="col-auto"><i class="bi bi-arrow-right"></i></div>
                                <div class="col">
                                    <select class="form-select"><option selected>Nome do inscrito</option><option>Responsável</option><option>Nome do evento</option></select>
                                </div>
                            </div>
                        </div>
                        <div class="vm-row">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto mono">@{{2}}</div>
                                <div class="col-auto"><i class="bi bi-arrow-right"></i></div>
                                <div class="col">
                                    <select class="form-select"><option>Nome do inscrito</option><option selected>Nome do evento</option><option>Local</option></select>
                                </div>
                            </div>
                        </div>
                        <div class="vm-row">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto mono">@{{3}}</div>
                                <div class="col-auto"><i class="bi bi-arrow-right"></i></div>
                                <div class="col">
                                    <select class="form-select"><option selected>Data do evento</option><option>Horário</option></select>
                                </div>
                            </div>
                        </div>
                        <div class="vm-row">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto mono">@{{4}}</div>
                                <div class="col-auto"><i class="bi bi-arrow-right"></i></div>
                                <div class="col">
                                    <select class="form-select"><option>Data do evento</option><option selected>Horário</option></select>
                                </div>
                            </div>
                        </div>
                        <div class="vm-row">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto mono">@{{5}}</div>
                                <div class="col-auto"><i class="bi bi-arrow-right"></i></div>
                                <div class="col">
                                    <select class="form-select"><option selected>Local + cidade</option><option>Link</option></select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between flex-wrap gap-2">
    <a href="{{ route('whatsapp-mensagens.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" form="fNovaMensagemWhatsapp" class="btn btn-primary"><i class="bi bi-check-lg"></i>Adicionar mensagem</button>
</div>
@endsection
