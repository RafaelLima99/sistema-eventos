@extends('layouts.app')

@section('title', 'Novo inscrito')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Adicionar inscrito</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('inscritos.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Inscritos</a>
    </div>
</div>

<form method="POST">
    @csrf
    <div class="d-flex flex-column gap-3">

        <div class="card">
            <div class="card-head"><h3>Dados do inscrito</h3></div>
            <div class="card-pad">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome completo</label>
                        <input class="form-control" placeholder="Ex.: Ana Silva">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">E-mail</label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input type="email" class="form-control" placeholder="nome@email.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telefone</label>
                        <div class="input-icon">
                            <i class="bi bi-telephone"></i>
                            <input class="form-control" placeholder="(11) 99012-3145">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CPF</label>
                        <div class="input-icon">
                            <i class="bi bi-person-vcard"></i>
                            <input class="form-control" placeholder="000.000.000-00" inputmode="numeric">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cargo</label>
                        <input class="form-control" placeholder="Ex.: Diretor(a) de Marketing">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Negócio</label>
                        <input class="form-control" placeholder="Ex.: Agência, SaaS B2B...">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head"><h3>Inscrição</h3></div>
            <div class="card-pad">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Evento</label>
                        <select class="form-select">
                            <option selected>Summit de Marketing Digital 2026</option>
                            <option>Workshop de Vendas B2B — Turma 12</option>
                            <option>Conferência de Produto &amp; Growth</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de ingresso</label>
                        <select class="form-select">
                            <option>VIP</option>
                            <option selected>Inteira</option>
                            <option>Meia</option>
                            <option>Cortesia</option>
                            <option>Estudante</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantidade</label>
                        <input type="number" class="form-control" value="1" min="1">
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Status do pagamento</label>
                        <select class="form-select">
                            <option>Pago</option>
                            <option selected>Pendente</option>
                            <option>Gratuito / Cortesia</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h3>Origem (UTM)</h3>
                <div class="right"><span class="sub">opcional</span></div>
            </div>
            <div class="card-pad">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">UTM Source</label>
                        <input class="form-control" placeholder="Ex.: instagram">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">UTM Medium</label>
                        <input class="form-control" placeholder="Ex.: social">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">UTM Campaign</label>
                        <input class="form-control" placeholder="Ex.: smd2026-lancamento">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">UTM Content</label>
                        <input class="form-control" placeholder="Ex.: story-01">
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between flex-wrap gap-2">
            <a href="{{ route('inscritos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i>Adicionar inscrito</button>
        </div>

    </div>
</form>
@endsection
