@extends('layouts.app')

@section('title', 'Automações')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Automações</h2>
        <p>Gatilhos disparados automaticamente conforme a jornada do inscrito.</p>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <form method="GET" class="d-flex flex-wrap gap-2">
            <select class="filter-select" name="evento">
                <option selected>Summit de Marketing Digital 2026</option>
                <option>Workshop de Vendas B2B — Turma 12</option>
                <option>Conferência de Produto &amp; Growth</option>
            </select>
            <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i>Buscar</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-head d-flex align-items-center">
        <h3>Gatilhos de comunicação</h3>
        <div class="right ms-auto">
            <a href="{{ route('automacoes.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i>Novo gatilho</a>
        </div>
    </div>
    <div class="card-pad">

        <div class="comm-card">
            <div class="comm-head d-flex align-items-center mb-3">
                <div class="comm-ic wa d-grid flex-shrink-0"><i class="bi bi-whatsapp"></i></div>
                <div class="comm-title flex-grow-1"><b>15 minutos após a inscrição · WhatsApp</b></div>
                <div class="comm-act d-flex align-items-center flex-shrink-0">
                    <a href="{{ route('automacoes.edit', 1) }}" class="btn btn-outline-secondary btn-sm" aria-label="Editar gatilho"><i class="bi bi-pencil"></i></a>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Público</label>
                    <div><span class="pill blue d-inline-flex align-items-center">Não pagantes</span></div>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Mensagem</label>
                    <div class="fw-semibold comm-msg">Lembrete de pagamento (15 min) · WhatsApp</div>
                </div>
            </div>
        </div>

        <div class="comm-card">
            <div class="comm-head d-flex align-items-center mb-3">
                <div class="comm-ic d-grid flex-shrink-0"><i class="bi bi-envelope"></i></div>
                <div class="comm-title flex-grow-1"><b>15 minutos após a inscrição · E-mail</b></div>
                <div class="comm-act d-flex align-items-center flex-shrink-0">
                    <a href="{{ route('automacoes.edit', 1) }}" class="btn btn-outline-secondary btn-sm" aria-label="Editar gatilho"><i class="bi bi-pencil"></i></a>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Público</label>
                    <div><span class="pill blue d-inline-flex align-items-center">Não pagantes</span></div>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Mensagem</label>
                    <div class="fw-semibold comm-msg">Lembrete de pagamento (15 min) · E-mail</div>
                </div>
            </div>
        </div>

        <div class="comm-card">
            <div class="comm-head d-flex align-items-center mb-3">
                <div class="comm-ic wa d-grid flex-shrink-0"><i class="bi bi-whatsapp"></i></div>
                <div class="comm-title flex-grow-1"><b>1 hora após a inscrição · WhatsApp</b></div>
                <div class="comm-act d-flex align-items-center flex-shrink-0">
                    <a href="{{ route('automacoes.edit', 1) }}" class="btn btn-outline-secondary btn-sm" aria-label="Editar gatilho"><i class="bi bi-pencil"></i></a>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Público</label>
                    <div><span class="pill blue d-inline-flex align-items-center">Não pagantes</span></div>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Mensagem</label>
                    <div class="fw-semibold comm-msg">Última chamada de pagamento (1h) · WhatsApp</div>
                </div>
            </div>
        </div>

        <div class="comm-card">
            <div class="comm-head d-flex align-items-center mb-3">
                <div class="comm-ic d-grid flex-shrink-0"><i class="bi bi-envelope"></i></div>
                <div class="comm-title flex-grow-1"><b>1 hora após a inscrição · E-mail</b></div>
                <div class="comm-act d-flex align-items-center flex-shrink-0">
                    <a href="{{ route('automacoes.edit', 1) }}" class="btn btn-outline-secondary btn-sm" aria-label="Editar gatilho"><i class="bi bi-pencil"></i></a>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Público</label>
                    <div><span class="pill blue d-inline-flex align-items-center">Não pagantes</span></div>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Mensagem</label>
                    <div class="fw-semibold comm-msg">Última chamada de pagamento (1h) · E-mail</div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
