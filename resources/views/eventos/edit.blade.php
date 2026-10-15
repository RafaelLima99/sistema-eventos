@extends('layouts.app')

@section('title', 'Editar evento')

@section('content')
<div class="page-title">
    <div>
        <h2>Editar evento</h2>
        <p>Atualize os dados do evento cadastrado.</p>
    </div>
</div>

<div class="d-flex flex-column gap-3">

    <div class="card">
        <div class="card-head"><h3>Dados do evento</h3></div>
        <div class="card-pad">
            <form method="POST" action="{{ route('eventos.update', $evento) }}" id="fEditarEvento">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nome do evento</label>
                        <input name="titulo" value="{{ old('titulo', $evento->titulo) }}" class="form-control @error('titulo') is-invalid @enderror">
                        @error('titulo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Data</label>
                        <input name="data_evento" value="{{ old('data_evento', $evento->data_evento->format('Y-m-d')) }}" class="form-control @error('data_evento') is-invalid @enderror" type="date">
                        @error('data_evento')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Início</label>
                        <input name="hora_inicio" value="{{ old('hora_inicio', $evento->hora_inicio) }}" class="form-control @error('hora_inicio') is-invalid @enderror" type="time">
                        @error('hora_inicio')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Término</label>
                        <input name="hora_fim" value="{{ old('hora_fim', $evento->hora_fim) }}" class="form-control @error('hora_fim') is-invalid @enderror" type="time">
                        @error('hora_fim')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Local</label>
                        <input name="local" value="{{ old('local', $evento->local) }}" class="form-control @error('local') is-invalid @enderror">
                        @error('local')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- Capacidade: ainda não existe coluna no banco, será implementada futuramente.
                    <div class="col-md-3"><label class="form-label">Capacidade</label><input class="form-control" type="number" value="150"></div>
                    --}}
                    <div class="col-md-3">
                        <label class="form-label">Valor do ingresso</label>
                        <div class="input-icon"><i class="bi bi-cash"></i><input name="preco_ingresso" value="{{ old('preco_ingresso', number_format($evento->preco_ingresso, 2, ',', '.')) }}" class="form-control @error('preco_ingresso') is-invalid @enderror"></div>
                        @error('preco_ingresso')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Endereço</label>
                        <input name="endereco" value="{{ old('endereco', $evento->endereco) }}" class="form-control @error('endereco') is-invalid @enderror">
                        @error('endereco')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Cidade</label>
                        <input name="cidade" value="{{ old('cidade', $evento->cidade) }}" class="form-control @error('cidade') is-invalid @enderror">
                        @error('cidade')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">UF</label>
                        <select name="uf" class="form-select @error('uf') is-invalid @enderror">
                            <option value="">UF</option>
                            @foreach (App\Enums\Uf::cases() as $uf)
                                <option value="{{ $uf->value }}" @selected(old('uf', $evento->uf->value) === $uf->value)>{{ $uf->value }}</option>
                            @endforeach
                        </select>
                        @error('uf')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Link de inscrição</label>
                        <div class="input-icon"><i class="bi bi-link-45deg"></i><input name="link_inscricao" value="{{ old('link_inscricao', $evento->link_inscricao) }}" class="form-control @error('link_inscricao') is-invalid @enderror"></div>
                        @error('link_inscricao')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Link de pagamento</label>
                        <div class="input-icon"><i class="bi bi-credit-card"></i><input name="link_pagamento" value="{{ old('link_pagamento', $evento->link_pagamento) }}" class="form-control @error('link_pagamento') is-invalid @enderror"></div>
                        @error('link_pagamento')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-end flex-wrap" style="gap:10px">
        <a href="{{ route('eventos.show', $evento) }}" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" form="fEditarEvento" class="btn btn-primary"><i class="bi bi-check-lg"></i>Salvar alterações</button>
    </div>

</div>
@endsection
