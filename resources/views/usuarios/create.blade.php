@extends('layouts.app')

@section('title', 'Novo usuário')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Novo usuário</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Usuários</a>
    </div>
</div>

<div class="d-flex flex-column gap-3">

    <div class="card">
        <div class="card-head"><h3>Dados do usuário</h3></div>
        <div class="card-pad pb-5">
            <form method="POST" id="fNovoUsuario">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome completo</label>
                        <input class="form-control" placeholder="Ex.: Marina Costa">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">E-mail</label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input type="email" class="form-control" placeholder="nome@empresa.com">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Papel</label>
                        <select class="form-select">
                            <option selected>Somente leitura</option>
                            <option>Operação</option>
                            <option>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Senha</label>
                        <div class="input-icon">
                            <i class="bi bi-lock"></i>
                            <input type="password" class="form-control" placeholder="Senha provisória" autocomplete="new-password">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between flex-wrap gap-2">
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" form="fNovoUsuario" class="btn btn-primary"><i class="bi bi-check-lg"></i>Criar usuário</button>
    </div>

</div>
@endsection
