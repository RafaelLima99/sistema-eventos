@extends('layouts.app')

@section('title', 'Detalhes da mensagem de e-mail')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>{{ $emailMensagem->titulo }}</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('email-mensagens.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Mensagens</a>
    </div>
</div>

<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <span class="chan-ic em"><i class="bi bi-envelope"></i></span>
    <span class="badge-status bs-blue"><span class="b-dot"></span>E-mail</span>
    <div class="ms-auto d-flex flex-wrap gap-2">
        <a href="{{ route('email-mensagens.edit', $emailMensagem) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i>Editar</a>
    </div>
</div>

<div class="d-flex flex-column gap-3">

    <div class="card">
        <div class="card-head"><h3>Dados no sistema</h3></div>
        <div class="card-pad">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nome no sistema</label>
                    <input class="form-control" value="{{ $emailMensagem->titulo }}" disabled>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h3>Conteúdo do e-mail</h3>
        </div>
        <div class="card-pad">
            <dl class="kv" style="grid-template-columns:170px 1fr">
                <dt>Assunto</dt><dd>{{ $emailMensagem->assunto }}</dd>
                <dt>Última edição</dt><dd>{{ $emailMensagem->updated_at->format('d/m/Y H:i') }}</dd>
            </dl>
            <div class="divider"></div>
            <label class="form-label">Conteúdo</label>
            <div class="tpl-body">{{ $emailMensagem->conteudo }}</div>
            <p class="form-hint mt-2">Precisa mudar o texto? <a href="{{ route('email-mensagens.edit', $emailMensagem) }}">Edite esta mensagem</a>.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-head"><h3>Variáveis usadas</h3></div>
        <div class="card-pad">
            <div>
                @forelse ($variaveisUsadas as $variavel)
                    <span class="var-token">{{ $variavel }}</span>
                @empty
                    <span class="text-muted">Nenhuma variável usada nesta mensagem.</span>
                @endforelse
            </div>
            <div class="form-hint">O e-mail usa variáveis nomeadas, escritas direto no conteúdo. Cada uma é preenchida com o valor do campo no momento do envio.</div>
        </div>
    </div>

</div>
@endsection
