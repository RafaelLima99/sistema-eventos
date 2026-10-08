@extends('layouts.app')

@section('title', 'Novo evento')

@section('content')
<div class="page-title">
    <div>
        <h2>Novo evento</h2>
        <p>Cadastre os dados do evento. Os gatilhos de comunicação são configurados na próxima etapa.</p>
    </div>
</div>

<div class="d-flex flex-column gap-3">

    <div class="card">
        <div class="card-head"><h3>Dados do evento</h3></div>
        <div class="card-pad">
            <form method="POST" id="fCriarEvento">
                @csrf
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Nome do evento</label><input class="form-control" placeholder="Ex.: Summit de Marketing Digital 2026"></div>
                    <div class="col-md-4"><label class="form-label">Data</label><input class="form-control" type="date"></div>
                    <div class="col-md-4"><label class="form-label">Início</label><input class="form-control" type="time" value="09:00"></div>
                    <div class="col-md-4"><label class="form-label">Término</label><input class="form-control" type="time" value="18:00"></div>
                    <div class="col-md-6"><label class="form-label">Local</label><input class="form-control" placeholder="Ex.: Centro de Convenções Frei Caneca"></div>
                    <div class="col-md-3"><label class="form-label">Capacidade</label><input class="form-control" type="number" placeholder="150"></div>
                    <div class="col-md-3"><label class="form-label">Valor do ingresso</label><div class="input-icon"><i class="bi bi-cash"></i><input class="form-control" placeholder="600,00"></div></div>
                    <div class="col-md-6"><label class="form-label">Endereço</label><input class="form-control" placeholder="Rua, número, bairro"></div>
                    <div class="col-md-4"><label class="form-label">Cidade</label><input class="form-control" placeholder="Ex.: São Paulo"></div>
                    <div class="col-md-2">
                        <label class="form-label">UF</label>
                        <select class="form-select">
                            <option value="">UF</option>
                            <option>AC</option>
                            <option>AL</option>
                            <option>AP</option>
                            <option>AM</option>
                            <option>BA</option>
                            <option>CE</option>
                            <option>DF</option>
                            <option>ES</option>
                            <option>GO</option>
                            <option>MA</option>
                            <option>MT</option>
                            <option>MS</option>
                            <option>MG</option>
                            <option>PA</option>
                            <option>PB</option>
                            <option>PR</option>
                            <option>PE</option>
                            <option>PI</option>
                            <option>RJ</option>
                            <option>RN</option>
                            <option>RS</option>
                            <option>RO</option>
                            <option>RR</option>
                            <option>SC</option>
                            <option selected>SP</option>
                            <option>SE</option>
                            <option>TO</option>
                        </select>
                    </div>
                    <div class="col-md-6"><label class="form-label">Link de inscrição</label><div class="input-icon"><i class="bi bi-link-45deg"></i><input class="form-control" placeholder="https://inscrever.eventosce.com/..."></div></div>
                    <div class="col-md-6"><label class="form-label">Link de pagamento</label><div class="input-icon"><i class="bi bi-credit-card"></i><input class="form-control" placeholder="https://pay.eventosce.com/..."></div></div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-end flex-wrap" style="gap:10px">
        <a href="{{ route('eventos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" form="fCriarEvento" class="btn btn-primary">Continuar <i class="bi bi-arrow-right"></i></button>
    </div>

</div>
@endsection
