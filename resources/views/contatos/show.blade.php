@extends('layouts.app')

@section('title', 'Detalhes do contato')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2 class="mb-0">Ana Silva</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('contatos.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Contatos</a>
    </div>
</div>

<div class="d-flex flex-column gap-3">

    <div class="card">
        <div class="card-head">
            <h3>Dados do contato</h3>
        </div>
        <div class="card-pad">
            <dl class="kv">
                <dt>E-mail</dt><dd>ana.silva@gmail.com</dd>
                <dt>Telefone</dt><dd>(11) 99012-3145</dd>
                <dt>CPF</dt><dd class="mono">312.048.271-11</dd>
                <dt>Cargo</dt><dd>CEO</dd>
                <dt>Negócio</dt><dd>Agência</dd>
                <dt>Cadastrado em</dt><dd>02/09/2026</dd>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h3>Eventos vinculados</h3>
        </div>
        <div class="table-wrap">
            <table class="data">
                <thead>
                    <tr>
                        <th>Evento</th>
                        <th>Inscrição</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="{{ route('eventos.show', 1) }}" class="strong">Summit de Marketing Digital 2026</a></td>
                        <td>02/09/2026</td>
                        <td><span class="badge-status bs-green"><span class="b-dot"></span>Pago</span></td>
                    </tr>
                    <tr>
                        <td><a href="{{ route('eventos.show', 1) }}" class="strong">Café com Founders — Edição Setembro</a></td>
                        <td>10/08/2026</td>
                        <td><span class="badge-status bs-blue"><span class="b-dot"></span>Concluído</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
