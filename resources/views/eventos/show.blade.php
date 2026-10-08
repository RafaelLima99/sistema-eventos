@extends('layouts.app')

@section('title', 'Detalhes do evento')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <h2 class="mb-0">Summit de Marketing Digital 2026</h2>
            <span class="badge-status bs-green"><span class="b-dot"></span>Publicado</span>
        </div>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('inscritos.index') }}" class="btn btn-outline-secondary"><i class="bi bi-people"></i>Inscritos</a>
        <a href="{{ route('automacoes.index') }}" class="btn btn-outline-secondary"><i class="bi bi-robot"></i>Automações</a>
        <a href="{{ route('presenca.index') }}" class="btn btn-outline-secondary"><i class="bi bi-qr-code-scan"></i>Presença</a>
        <a href="{{ route('eventos.edit', 1) }}" class="btn btn-primary"><i class="bi bi-pencil"></i>Editar evento</a>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <h3>Dados do evento</h3>
    </div>
    <div class="card-pad">
        <dl class="kv">
            <dt>Nome</dt><dd>Summit de Marketing Digital 2026</dd>
            <dt>Data</dt><dd>18/09/2026 (sexta-feira)</dd>
            <dt>Horário</dt><dd>09:00 às 18:00</dd>
            <dt>Local</dt><dd>Centro de Convenções Frei Caneca</dd>
            <dt>Endereço</dt><dd>R. Frei Caneca, 569 — Consolação, São Paulo/SP</dd>
            <dt>Capacidade</dt><dd>150 vagas</dd>
            <dt>Link de inscrição</dt><dd><a href="#" class="mono">inscrever.eventosce.com/smd-2026</a></dd>
            <dt>Link de pagamento</dt><dd><a href="#" class="mono">pay.eventosce.com/smd-2026</a></dd>
        </dl>
    </div>
</div>
@endsection
