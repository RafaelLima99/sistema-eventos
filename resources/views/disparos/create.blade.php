@extends('layouts.app')

@section('title', 'Novo disparo')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Novo disparo</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('disparos.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Disparos</a>
    </div>
</div>

<form method="POST" action="{{ route('disparos.store') }}" id="fDisparo">
    @csrf
    @include('disparos._campos')

    {{-- O botão clicado define o status: rascunho (não envia) ou agendado. --}}
    <div class="d-flex justify-content-between flex-wrap gap-2">
        <a href="{{ route('disparos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        <div class="d-flex flex-wrap gap-2">
            <button type="submit" name="status" value="rascunho" class="btn btn-outline-secondary"><i class="bi bi-save"></i>Salvar rascunho</button>
            <button type="submit" name="status" value="agendado" class="btn btn-primary" onclick="return confirm('Agendar o envio deste disparo?')"><i class="bi bi-send"></i>Agendar envio</button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/disparo.js') }}"></script>
@endpush
