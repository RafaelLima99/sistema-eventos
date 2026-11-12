@extends('layouts.app')

@section('title', 'Editar mensagem de e-mail')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Editar mensagem de E-mail</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('email-mensagens.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Mensagens</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-pad">
        <form method="POST" action="{{ route('email-mensagens.update', $emailMensagem) }}" id="fEditarMensagemEmail">
            @csrf
            @method('PUT')
            <div class="row g-3">

                <div>
                    <label class="form-label">Nome no sistema</label>
                    <input name="titulo" value="{{ old('titulo', $emailMensagem->titulo) }}" class="form-control @error('titulo') is-invalid @enderror" placeholder="Ex.: Confirmação de inscrição · E-mail">
                    @error('titulo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Assunto</label>
                    <input name="assunto" value="{{ old('assunto', $emailMensagem->assunto) }}" class="form-control @error('assunto') is-invalid @enderror" placeholder="Ex.: Inscrição confirmada — @{{evento}}">
                    @error('assunto')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Conteúdo</label>
                    <textarea name="conteudo" rows="7" class="form-control @error('conteudo') is-invalid @enderror" placeholder="Olá @{{nome}},&#10;&#10;Sua inscrição no @{{evento}} foi confirmada...">{{ old('conteudo', $emailMensagem->conteudo) }}</textarea>
                    @error('conteudo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Variáveis disponíveis <span class="text-muted2 fw-normal">(clique para inserir)</span></label>
                    <div>
                        @foreach ($variaveisDisponiveis as $variavel)
                            <span class="var-token">{{ $variavel }}</span>
                        @endforeach
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between flex-wrap gap-2">
    <a href="{{ route('email-mensagens.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    <button type="submit" form="fEditarMensagemEmail" class="btn btn-primary"><i class="bi bi-check-lg"></i>Salvar alterações</button>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/inserir-variavel.js') }}"></script>
@endpush
