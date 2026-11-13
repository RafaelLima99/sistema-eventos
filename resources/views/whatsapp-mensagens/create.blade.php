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
        <form method="POST" action="{{ route('whatsapp-mensagens.store') }}" id="fNovaMensagemWhatsapp">
            @csrf
            @include('whatsapp-mensagens._campos')
        </form>
    </div>
</div>

<div class="d-flex justify-content-between flex-wrap gap-2">
    <a href="{{ route('whatsapp-mensagens.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" form="fNovaMensagemWhatsapp" class="btn btn-primary"><i class="bi bi-check-lg"></i>Adicionar mensagem</button>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/whatsapp-template.js') }}"></script>
@endpush
