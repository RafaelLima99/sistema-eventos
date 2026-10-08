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
                <form method="POST" id="fEditarMensagemEmail">
                    @csrf
                    <div class="row g-3">

                        <div>
                            <label class="form-label">Nome no sistema</label>
                            <input class="form-control" value="Confirmação de inscrição · E-mail">
                        </div>

                        <div>
                            <label class="form-label">Assunto</label>
                            <input class="form-control" value="Inscrição confirmada — @{{evento}}">
                        </div>

                        <div>
                            <label class="form-label">Conteúdo</label>
                            <textarea class="form-control" rows="7">Olá @{{nome}},

Sua inscrição no @{{evento}} foi confirmada.
Data: @{{data}} às @{{horario}}
Local: @{{localidade}}</textarea>
                        </div>

                        <div>
                            <label class="form-label">Variáveis disponíveis <span class="text-muted2 fw-normal">(clique para inserir)</span></label>
                            <div>
                                <span class="var-token">@{{nome}}</span>
                                <span class="var-token">@{{evento}}</span>
                                <span class="var-token">@{{data}}</span>
                                <span class="var-token">@{{horario}}</span>
                                <span class="var-token">@{{localidade}}</span>
                                <span class="var-token">@{{link}}</span>
                                <span class="var-token">@{{responsavel}}</span>
                                <span class="var-token">@{{tipo_ingresso}}</span>
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
