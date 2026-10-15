@extends('layouts.app')

@section('title', 'Detalhes do evento')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <div class="d-flex align-items-center flex-wrap gap-2">
            <h2 class="mb-0">{{ $evento->titulo }}</h2>
            <span class="badge-status {{ $evento->status->badgeClass() }}"><span class="b-dot"></span>{{ $evento->status->label() }}</span>
        </div>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('inscritos.index') }}" class="btn btn-outline-secondary"><i class="bi bi-people"></i>Inscritos</a>
        <a href="{{ route('automacoes.index') }}" class="btn btn-outline-secondary"><i class="bi bi-robot"></i>Automações</a>
        <a href="{{ route('presenca.index') }}" class="btn btn-outline-secondary"><i class="bi bi-qr-code-scan"></i>Presença</a>
        <a href="{{ route('eventos.edit', $evento) }}" class="btn btn-primary"><i class="bi bi-pencil"></i>Editar evento</a>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <h3>Dados do evento</h3>
    </div>
    <div class="card-pad">
        <dl class="kv">
            <dt>Nome</dt><dd>{{ $evento->titulo }}</dd>
            <dt>Data</dt><dd>{{ $evento->data_evento->locale('pt_BR')->translatedFormat('d/m/Y (l)') }}</dd>
            <dt>Horário</dt><dd>{{ $evento->hora_inicio }}@if ($evento->hora_fim) às {{ $evento->hora_fim }}@endif</dd>
            <dt>Local</dt><dd>{{ $evento->local }}</dd>
            <dt>Endereço</dt><dd>@if ($evento->endereco){{ $evento->endereco }}, @endif{{ $evento->cidade }}/{{ $evento->uf->value }}</dd>
            <dt>Valor do ingresso</dt><dd>R$ {{ number_format($evento->preco_ingresso, 2, ',', '.') }}</dd>
            {{-- Capacidade: ainda não existe coluna no banco, será implementada futuramente.
            <dt>Capacidade</dt><dd>150 vagas</dd>
            --}}
            <dt>Link de inscrição</dt><dd>@if ($evento->link_inscricao)<a href="{{ $evento->link_inscricao }}" target="_blank" class="mono">{{ $evento->link_inscricao }}</a>@else — @endif</dd>
            <dt>Link de pagamento</dt><dd>@if ($evento->link_pagamento)<a href="{{ $evento->link_pagamento }}" target="_blank" class="mono">{{ $evento->link_pagamento }}</a>@else — @endif</dd>
        </dl>
    </div>
</div>
@endsection
