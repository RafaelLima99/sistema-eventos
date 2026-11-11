@extends('layouts.app')

@section('title', 'Controle de presença')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>{{ $evento ? 'Credenciamento — '.$evento->titulo : 'Credenciamento' }}</h2>
    </div>
</div>

<div class="card">
    <div class="toolbar">
        <form method="GET" class="row g-2 flex-grow-1 align-items-center">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="search-inline">
                    <i class="bi bi-search"></i>
                    <input type="text" name="busca_nome" value="{{ request('busca_nome') }}" placeholder="Busca por nome">
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="search-inline">
                    <i class="bi bi-person-vcard"></i>
                    <input type="text" name="cpf" data-mascara-cpf value="{{ request('cpf') }}" placeholder="Busca por CPF" inputmode="numeric" maxlength="14">
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl">
                <select class="filter-select w-100" name="evento">
                    @foreach ($eventos as $opcao)
                        <option value="{{ $opcao->id }}" @selected($opcao->id === $evento?->id)>{{ $opcao->titulo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6 col-xl-auto d-grid d-xl-block">
                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i>Buscar</button>
            </div>
        </form>
    </div>

    <div>
        @forelse ($inscritos as $inscrito)
            @php($contato = $inscrito->contato)
            @php($jaPresente = $inscrito->status_presenca === \App\Enums\StatusPresenca::Presente)
            <div class="p-row px-3 py-2 {{ $jaPresente ? 'checked' : '' }}">
                <div class="row gx-3 gy-2 align-items-center">
                    <div class="col-12 col-md d-flex align-items-center gap-2">
                        <span class="avatar avatar-cor-{{ $contato->id % 6 }}">{{ $contato->iniciaisDoNome() }}</span>
                        <div class="who">
                            <b class="d-block">{{ $contato->nome }}</b>
                            <small>{{ $contato->email ?? '—' }} · {{ $contato->telefone ?? '—' }} · CPF {{ $contato->cpfFormatado() ?? '—' }}</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-auto">
                        @if ($inscrito->quantidade_ingresso)
                            <span class="pill"><i class="bi bi-ticket-perforated"></i>{{ $inscrito->quantidade_ingresso }} {{ $inscrito->quantidade_ingresso === 1 ? 'ingresso' : 'ingressos' }}</span>
                        @endif
                    </div>
                    <div class="col-6 col-md-auto text-end">
                        @if ($jaPresente)
                            <button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-check-lg"></i>Presente</button>
                        @else
                            <form method="POST" action="{{ route('presenca.store', $inscrito) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-check-lg"></i>Confirmar Presença</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">Nenhum inscrito encontrado.</div>
        @endforelse
    </div>

    @if ($inscritos->hasPages())
        <div class="table-foot d-flex align-items-center">
            {{ $inscritos->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/mascara-cpf.js') }}"></script>
@endpush
