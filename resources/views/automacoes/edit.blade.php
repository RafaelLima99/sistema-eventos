@extends('layouts.app')

@section('title', 'Editar gatilho')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Editar gatilho de comunicação</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('automacoes.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Automações</a>
    </div>
</div>

<form method="POST" action="{{ route('automacoes.update', $automacao) }}" id="fEditarGatilho" class="mb-4">
    @csrf
    @method('PUT')
    @include('automacoes._campos')
</form>

<div class="d-flex justify-content-between flex-wrap gap-2">
    <a href="{{ route('automacoes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" form="fEditarGatilho" class="btn btn-primary"><i class="bi bi-check-lg"></i>Salvar alterações</button>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/automacao-canal.js') }}"></script>
@endpush
