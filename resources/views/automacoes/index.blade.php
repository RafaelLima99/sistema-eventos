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
                <option value="">Todos os eventos</option>
                @foreach ($eventos as $evento)
                    <option value="{{ $evento->id }}" @selected(request('evento') == $evento->id)>{{ $evento->titulo }}</option>
                @endforeach
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

        @forelse ($automacoes as $automacao)
            <div class="comm-card">
                <div class="comm-head d-flex align-items-center mb-3">
                    <div class="comm-ic {{ $automacao->canal === \App\Enums\CanalMensagem::Whatsapp ? 'wa' : '' }} d-grid flex-shrink-0"><i class="bi {{ $automacao->canal->icone() }}"></i></div>
                    <div class="comm-title flex-grow-1"><b>{{ $automacao->titulo }}</b></div>
                    <div class="comm-act d-flex align-items-center flex-shrink-0">
                        <span class="badge-status {{ $automacao->status->badgeClass() }}"><span class="b-dot"></span>{{ $automacao->status->label() }}</span>
                        <a href="{{ route('automacoes.edit', $automacao) }}" class="btn btn-outline-secondary btn-sm" aria-label="Editar gatilho"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('automacoes.destroy', $automacao) }}" onsubmit="return confirm(@js('Deseja remover "'.$automacao->titulo.'"?'))">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary btn-sm" aria-label="Remover gatilho"><i class="bi bi-trash text-danger"></i></button>
                        </form>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Quando</label>
                        @php($horas = intdiv($automacao->minutos_disparo, 60))
                        @php($minutos = $automacao->minutos_disparo % 60)
                        <div class="comm-msg">
                            @if ($automacao->minutos_disparo === 0)
                                Logo após a inscrição
                            @else
                                {{ $horas > 0 ? $horas.'h' : '' }} {{ $minutos > 0 ? $minutos.'min' : '' }} após a inscrição
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Público</label>
                        <div><span class="pill blue d-inline-flex align-items-center">{{ $automacao->filtro->label() }}</span></div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Mensagem</label>
                        <div class="fw-semibold comm-msg">{{ $automacao->mensagem()?->titulo ?? '—' }} · {{ $automacao->canal->label() }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Evento</label>
                        <div class="comm-msg">{{ $automacao->evento->titulo }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">Nenhuma automação encontrada.</div>
        @endforelse

    </div>

    @if ($automacoes->hasPages())
        <div class="table-foot d-flex align-items-center">
            {{ $automacoes->links() }}
        </div>
    @endif
</div>
@endsection
