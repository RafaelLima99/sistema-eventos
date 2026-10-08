@extends('layouts.app')

@section('title', 'Novo gatilho')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Adicionar gatilho de comunicação</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('eventos.create') }}" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" form="fNovoGatilho" class="btn btn-primary"><i class="bi bi-check-lg"></i>Salvar gatilho</button>
    </div>
</div>

<form method="POST" id="fNovoGatilho">
    @csrf
    <div class="d-flex flex-column gap-3">

        <div class="card">
            <div class="card-head"><h3>Quando disparar</h3></div>
            <div class="card-pad">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tempo após a inscrição</label>
                        <input class="form-control" type="time" name="tempo_disparo" value="00:00">
                    </div>
                </div>
                <div class="form-hint">Horas e minutos após a inscrição — ex.: 01:30 dispara 1h30 depois. Deixe 00:00 para disparo imediato.</div>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><h3>Público</h3></div>
            <div class="card-pad">
                <div class="d-flex flex-wrap gap-2">
                    <span class="aud-btn on"><i class="bi bi-check-circle-fill"></i>Não pagantes</span>
                    <span class="aud-btn">Pagantes</span>
                    <span class="aud-btn">Toda a base</span>
                    <span class="aud-btn">Presentes</span>
                    <span class="aud-btn">Ausentes</span>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><h3>Mensagem</h3></div>
            <div class="card-pad">
                <label class="form-label">Canal</label>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="aud-btn on"><i class="bi bi-whatsapp"></i>WhatsApp</span>
                    <span class="aud-btn"><i class="bi bi-envelope"></i>E-mail</span>
                </div>
                <label class="form-label">Template de mensagem</label>
                <div class="d-flex flex-wrap gap-2">
                    <select class="form-select flex-grow-1" style="flex:1;min-width:220px">
                        <option selected>Lembrete de pagamento (15 min) · WhatsApp — Aprovado</option>
                        <option>Última chamada de pagamento (1h) · WhatsApp — Aprovado</option>
                        <option>Confirmação de inscrição · WhatsApp — Aprovado</option>
                        <option>No dia do evento · WhatsApp — Aprovado</option>
                    </select>
                    <a href="{{ route('whatsapp-mensagens.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-up-right"></i>Ver</a>
                </div>
            </div>
        </div>

        <div class="switch-row d-flex align-items-center">
            <div class="flex-grow-1" style="flex:1"><b>Ativar gatilho ao salvar</b><br><small>Se desativado, fica em rascunho e não dispara</small></div>
            <div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('eventos.create') }}" class="btn btn-outline-secondary"><i class="bi bi-chevron-left"></i>Voltar</a>
            <button type="submit" form="fNovoGatilho" class="btn btn-primary"><i class="bi bi-check-lg"></i>Salvar gatilho</button>
        </div>

    </div>
</form>
@endsection
