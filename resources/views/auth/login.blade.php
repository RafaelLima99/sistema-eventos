@extends('layouts.auth')

@section('title', 'Entrar')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 px-4 px-sm-3">

            @if (session('erro'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="bi bi-exclamation-circle"></i>{{ session('erro') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            @endif

            <div class="card login-card">
                <div class="card-pad p-4">
                    <div class="d-flex flex-column align-items-center text-center mb-4">
                        <div class="login-logo d-grid mb-3"><i class="bi bi-graph-up-arrow"></i></div>
                        <b style="font-size:18px">Gestão de Eventos</b>
                    </div>

                    <form method="POST" action="{{ route('login.store') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="loginEmail">E-mail</label>
                            <div class="input-icon">
                                <i class="bi bi-envelope"></i>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="loginEmail" placeholder="voce@empresa.com" autocomplete="email" required autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="loginSenha">Senha</label>
                            <div class="input-icon">
                                <i class="bi bi-lock"></i>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="loginSenha" placeholder="Sua senha" autocomplete="current-password" required>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right"></i>Entrar</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
