@extends('layouts.auth')

@section('title', 'Entrar')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4 px-4 px-sm-3">

            <div class="card login-card">
                <div class="card-pad p-4">
                    <div class="d-flex flex-column align-items-center text-center mb-4">
                        <div class="login-logo d-grid mb-3"><i class="bi bi-graph-up-arrow"></i></div>
                        <b style="font-size:18px">Gestão de Eventos</b>
                    </div>

                    <form method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="loginEmail">E-mail</label>
                            <div class="input-icon">
                                <i class="bi bi-envelope"></i>
                                <input type="email" class="form-control" id="loginEmail" placeholder="voce@empresa.com" autocomplete="email">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="loginSenha">Senha</label>
                            <div class="input-icon">
                                <i class="bi bi-lock"></i>
                                <input type="password" class="form-control" id="loginSenha" placeholder="Sua senha" autocomplete="current-password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right"></i>Entrar</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
