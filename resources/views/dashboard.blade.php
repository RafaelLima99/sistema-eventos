@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- ============ CONTENT ============ -->


  <div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
      <h2>Bem-vindo de volta, Rafael 👋</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
      <form method="GET" class="d-flex flex-wrap gap-2">
        <select class="filter-select" name="evento">
          <option>Todos os eventos</option>
          <option selected>Summit de Marketing Digital 2026</option>
          <option>Workshop de Vendas B2B — Turma 12</option>
          <option>Conferência de Produto &amp; Growth</option>
        </select>
        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i>Buscar</button>
      </form>
      <a href="{{ route('eventos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i>Novo evento</a>
    </div>
  </div>

  <!-- KPIs -->
  <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
    <div class="col">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <span class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-3 p-2 mb-3">
            <i class="bi bi-people fs-4"></i>
          </span>
          <div class="small text-secondary">Inscritos</div>
          <div class="fs-3 fw-bold text-dark">128</div>
          <span class="small text-success d-inline-flex align-items-center gap-1"><i class="bi bi-arrow-up-right"></i>+18 nos últimos 7 dias</span>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <span class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-3 p-2 mb-3">
            <i class="bi bi-cash-coin fs-4"></i>
          </span>
          <div class="small text-secondary">Pagantes</div>
          <div class="fs-3 fw-bold text-dark">72</div>
          <span class="small text-success d-inline-flex align-items-center gap-1"><i class="bi bi-arrow-up-right"></i>+9 nos últimos 7 dias</span>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <span class="d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning-emphasis rounded-3 p-2 mb-3">
            <i class="bi bi-hourglass-split fs-4"></i>
          </span>
          <div class="small text-secondary">Não pagantes</div>
          <div class="fs-3 fw-bold text-dark">56</div>
          <span class="small text-secondary d-inline-flex align-items-center gap-1"><i class="bi bi-dash"></i>43,8% da base</span>
        </div>
      </div>
    </div>
  </div>
@endsection
