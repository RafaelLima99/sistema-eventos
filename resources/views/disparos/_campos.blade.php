{{-- Campos do formulário de disparo. Usado por create e edit. $disparo só existe na edição. --}}
@php
    // Valores que já vêm marcados ao abrir: os de antes de um erro de validação ou, na edição, os salvos.
    $todosContatos = (string) old('todos_contatos', isset($disparo) ? (int) $disparo->todos_contatos : 1);
    $eventosMarcados = old('eventos', isset($disparo) ? $disparo->eventos->pluck('id')->all() : []);
    $filtroEscolhido = old('filtro', $disparo->filtro->value ?? 'sem_filtro');
@endphp

<div class="row g-3 align-items-start mb-4">

    <!-- Coluna esquerda: para quem e quando -->
    <div class="col-lg-7 d-flex flex-column gap-3">

        <div class="card">
            <div class="card-head">
                <h3><span class="pill blue">1</span>&nbsp;Contatos</h3>
            </div>
            <div class="card-pad">
                <div class="row g-2">
                    <div class="col-12 col-sm-6">
                        <label class="opt-card">
                            <input type="radio" name="todos_contatos" value="1" class="visually-hidden" @checked($todosContatos === '1')>
                            <div class="oc-ic"><i class="bi bi-people"></i></div>
                            <b>Todos os contatos</b>
                        </label>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="opt-card">
                            <input type="radio" name="todos_contatos" value="0" class="visually-hidden" @checked($todosContatos === '0')>
                            <div class="oc-ic"><i class="bi bi-calendar-event"></i></div>
                            <b>Inscritos em evento</b>
                        </label>
                    </div>
                </div>

                {{-- Só aparece com "Inscritos em evento" (disparo.js). --}}
                <div id="contatosEventos" class="mt-3 d-none">
                    <div class="search-inline w-100 mb-2">
                        <i class="bi bi-search"></i>
                        <input type="text" id="buscaEvento" placeholder="Buscar evento por nome...">
                    </div>
                    <div class="ev-pick">
                        @foreach ($eventos as $evento)
                            <label class="ev-opt">
                                <input type="checkbox" name="eventos[]" value="{{ $evento->id }}" @checked(in_array($evento->id, $eventosMarcados))>
                                <div class="ev-name">{{ $evento->titulo }}</div>
                            </label>
                        @endforeach
                    </div>
                    @error('eventos')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-hint">Seleciona os contatos inscritos nos eventos marcados. Marque um ou mais eventos.</div>
                </div>
                <div class="form-hint">"Todos os contatos" atinge toda a base de contatos, mesmo quem nunca se inscreveu em um evento.</div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h3><span class="pill blue">2</span>&nbsp;Filtro</h3>
            </div>
            <div class="card-pad">
                <div class="row g-3">
                    <div class="col-md-6">
                        <select name="filtro" class="form-select @error('filtro') is-invalid @enderror">
                            @foreach (\App\Enums\FiltroPublico::cases() as $filtro)
                                <option value="{{ $filtro->value }}" @selected($filtroEscolhido === $filtro->value)>{{ $filtro->label() }}</option>
                            @endforeach
                        </select>
                        @error('filtro')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h3><span class="pill blue">3</span>&nbsp;Quando enviar</h3>
            </div>
            <div class="card-pad">
                {{-- O banco guarda data e hora juntas; na edição, elas são separadas de volta em cada campo. --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Data</label>
                        <input name="data" type="date" value="{{ old('data', isset($disparo) ? $disparo->agendado_para?->format('Y-m-d') : '') }}" class="form-control @error('data') is-invalid @enderror">
                        @error('data')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Hora</label>
                        <input name="hora" type="time" value="{{ old('hora', isset($disparo) ? $disparo->agendado_para?->format('H:i') : '') }}" class="form-control @error('hora') is-invalid @enderror">
                        @error('hora')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-hint">Deixe data e hora em branco para enviar assim que o disparo for agendado.</div>
            </div>
        </div>

    </div>

    <!-- Coluna direita: mensagem -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-head">
                <h3><span class="pill blue">4</span>&nbsp;Mensagem</h3>
                <div class="right"><span class="sub">WhatsApp</span></div>
            </div>
            <div class="card-pad">
                <label class="form-label">Mensagem de WhatsApp</label>
                <select name="whatsapp_mensagem_id" class="form-select @error('whatsapp_mensagem_id') is-invalid @enderror">
                    <option value="">Escolha uma mensagem...</option>
                    @foreach ($whatsappMensagens as $mensagem)
                        <option value="{{ $mensagem->id }}" @selected(old('whatsapp_mensagem_id', $disparo->whatsapp_mensagem_id ?? null) == $mensagem->id)>{{ $mensagem->titulo }}</option>
                    @endforeach
                </select>
                @error('whatsapp_mensagem_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="form-hint">O texto e o vínculo das variáveis vêm da mensagem escolhida. <a href="{{ route('whatsapp-mensagens.index') }}">Ver mensagens</a></div>
            </div>
        </div>
    </div>

</div>
