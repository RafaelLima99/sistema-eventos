@extends('layouts.app')

@section('title', 'Detalhes da mensagem de e-mail')

@section('content')
        <div class="page-title d-flex align-items-start gap-3 flex-wrap">
            <div>
                <h2>Confirmação de inscrição · E-mail</h2>
            </div>
            <div class="actions ms-auto d-flex flex-wrap">
                <a href="{{ route('email-mensagens.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Mensagens</a>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <span class="chan-ic em"><i class="bi bi-envelope"></i></span>
            <span class="badge-status bs-blue"><span class="b-dot"></span>E-mail</span>
            <div class="ms-auto d-flex flex-wrap gap-2">
                <a href="#" class="btn btn-outline-secondary btn-sm"><i class="bi bi-files"></i>Duplicar</a>
                <a href="{{ route('email-mensagens.edit', 1) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i>Editar</a>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">

            <div class="card">
                <div class="card-head"><h3>Dados no sistema</h3></div>
                <div class="card-pad">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nome no sistema</label>
                            <input class="form-control" value="Confirmação de inscrição · E-mail" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-head">
                    <h3>Conteúdo do e-mail</h3>
                </div>
                <div class="card-pad">
                    <div class="note-box mb-3">
                        <i class="bi bi-info-circle flex-shrink-0"></i>
                        <span>E-mail não passa por aprovação. Para mudar o assunto, o remetente ou o texto, edite a mensagem.</span>
                    </div>
                    <dl class="kv" style="grid-template-columns:170px 1fr">
                        <dt>Assunto</dt><dd>Inscrição confirmada — @{{evento}}</dd>
                        <dt>Remetente</dt><dd class="mono">Eventos CE &lt;eventos@unu.com.br&gt;</dd>
                        <dt>Última edição</dt><dd>hoje, 08:40</dd>
                    </dl>
                    <div class="divider"></div>
                    <label class="form-label">Conteúdo</label>
                    <div class="tpl-body">Olá @{{nome}},

Sua inscrição no @{{evento}} foi confirmada.
Data: @{{data}} às @{{horario}}
Local: @{{localidade}}</div>
                    <p class="form-hint mt-2">Precisa mudar o texto? <a href="{{ route('email-mensagens.edit', 1) }}">Edite esta mensagem</a>.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-head"><h3>Variáveis usadas</h3></div>
                <div class="card-pad">
                    <div>
                        <span class="var-token">@{{nome}}</span>
                        <span class="var-token">@{{evento}}</span>
                        <span class="var-token">@{{data}}</span>
                        <span class="var-token">@{{horario}}</span>
                        <span class="var-token">@{{localidade}}</span>
                    </div>
                    <div class="form-hint">O e-mail usa variáveis nomeadas, escritas direto no conteúdo. Cada uma é preenchida com o valor do campo no momento do envio.</div>
                </div>
            </div>

        </div>
@endsection
