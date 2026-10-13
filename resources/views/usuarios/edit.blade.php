@extends('layouts.app')

@section('title', 'Editar usuário')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>Editar usuário</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Usuários</a>
    </div>
</div>

<div class="d-flex flex-column gap-3">

    <div class="card">
        <div class="card-head"><h3>Dados do usuário</h3></div>
        <div class="card-pad pb-5">
            <form method="POST" action="{{ route('usuarios.update', $usuario) }}" id="fEditarUsuario">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nome completo</label>
                        <input name="name" value="{{ old('name', $usuario->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">E-mail</label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="email" value="{{ old('email', $usuario->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Papel: será implementado futuramente.
                    <div class="col-md-6">
                        <label class="form-label">Papel</label>
                        <select class="form-select">
                            <option>Somente leitura</option>
                            <option selected>Operação</option>
                            <option>Admin</option>
                        </select>
                    </div>
                    --}}
                    <div class="col-md-6">
                        <label class="form-label">Nova senha</label>
                        <div class="input-icon">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Deixe em branco para manter a atual" autocomplete="new-password">
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between flex-wrap gap-2">
        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" form="fEditarUsuario" class="btn btn-primary"><i class="bi bi-check-lg"></i>Salvar alterações</button>
    </div>

</div>
@endsection
