{{-- Campos do formulário de automação. Usado por create e edit. $automacao só existe na edição. --}}
{{-- Canal já marcado ao abrir: o de antes de um erro de validação ou, na edição, o salvo. Na criação, nenhum. --}}
@php($canalEscolhido = old('canal', $automacao->canal->value ?? null))

<div class="d-flex flex-column gap-3">

    <div class="card">
        <div class="card-head"><h3>Dados</h3></div>
        <div class="card-pad">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nome do gatilho</label>
                    <input name="titulo" value="{{ old('titulo', $automacao->titulo ?? '') }}" class="form-control @error('titulo') is-invalid @enderror" placeholder="Ex.: Lembrete de pagamento (15 min)">
                    @error('titulo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Evento</label>
                    <select name="evento_id" class="form-select @error('evento_id') is-invalid @enderror">
                        <option value="">Escolha um evento...</option>
                        @foreach ($eventos as $evento)
                            <option value="{{ $evento->id }}" @selected(old('evento_id', $automacao->evento_id ?? null) == $evento->id)>{{ $evento->titulo }}</option>
                        @endforeach
                    </select>
                    @error('evento_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head"><h3>Quando disparar</h3></div>
        <div class="card-pad">
            {{-- O banco guarda o total em minutos; na edição, ele é dividido de volta em horas e minutos (90 => 1h 30min). --}}
            @php($totalDeMinutos = $automacao->minutos_disparo ?? 0)
            <label class="form-label">Tempo após a inscrição</label>
            <div class="row g-3">
                <div class="col-6 col-md-2">
                    <div class="input-group">
                        <input name="horas" type="number" min="0" value="{{ old('horas', intdiv($totalDeMinutos, 60)) }}" class="form-control @error('horas') is-invalid @enderror">
                        <span class="input-group-text">h</span>
                    </div>
                    @error('horas')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-6 col-md-2">
                    <div class="input-group">
                        <input name="minutos" type="number" min="0" max="59" value="{{ old('minutos', $totalDeMinutos % 60) }}" class="form-control @error('minutos') is-invalid @enderror">
                        <span class="input-group-text">min</span>
                    </div>
                    @error('minutos')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-hint">Ex.: 1 h e 30 min dispara 1h30 depois. Deixe 0 h e 0 min para disparo imediato.</div>
        </div>
    </div>

    <div class="card">
        <div class="card-head"><h3>Público</h3></div>
        <div class="card-pad">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="filtro" class="form-select @error('filtro') is-invalid @enderror">
                        @foreach (\App\Enums\FiltroPublico::cases() as $filtro)
                            <option value="{{ $filtro->value }}" @selected(old('filtro', $automacao->filtro->value ?? null) === $filtro->value)>{{ $filtro->label() }}</option>
                        @endforeach
                    </select>
                    @error('filtro')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- O JavaScript (automacao-canal.js) mostra só o select de mensagem do canal escolhido. --}}
    <div class="card">
        <div class="card-head"><h3>Mensagem</h3></div>
        <div class="card-pad">
            <label class="form-label">Canal</label>
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach (\App\Enums\CanalMensagem::cases() as $canal)
                    <label class="aud-btn">
                        <input type="radio" name="canal" id="canal-{{ $canal->value }}" value="{{ $canal->value }}" @checked($canalEscolhido === $canal->value)>
                        <i class="bi {{ $canal->icone() }}"></i>{{ $canal->label() }}
                    </label>
                @endforeach
            </div>
            @error('canal')
                <div class="invalid-feedback d-block mb-3">Escolha o canal da mensagem.</div>
            @enderror

            <div class="row g-3">
                <div class="col-12 mensagem-whatsapp">
                    <label class="form-label">Mensagem de WhatsApp</label>
                    <select name="whatsapp_mensagem_id" class="form-select @error('whatsapp_mensagem_id') is-invalid @enderror">
                        <option value="">Escolha uma mensagem...</option>
                        @foreach ($whatsappMensagens as $mensagem)
                            <option value="{{ $mensagem->id }}" @selected(old('whatsapp_mensagem_id', $automacao->whatsapp_mensagem_id ?? null) == $mensagem->id)>{{ $mensagem->titulo }}</option>
                        @endforeach
                    </select>
                    @error('whatsapp_mensagem_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 mensagem-email">
                    <label class="form-label">Mensagem de e-mail</label>
                    <select name="email_mensagem_id" class="form-select @error('email_mensagem_id') is-invalid @enderror">
                        <option value="">Escolha uma mensagem...</option>
                        @foreach ($emailMensagens as $mensagem)
                            <option value="{{ $mensagem->id }}" @selected(old('email_mensagem_id', $automacao->email_mensagem_id ?? null) == $mensagem->id)>{{ $mensagem->titulo }}</option>
                        @endforeach
                    </select>
                    @error('email_mensagem_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="switch-row d-flex align-items-center">
        <div class="flex-grow-1"><b>Gatilho ativo</b><br><small>Se desativado, fica salvo mas não dispara</small></div>
        <div class="form-check form-switch">
            {{-- Desmarcado, só o campo escondido é enviado ("inativo"). Marcado, o checkbox vem depois e vale "ativo". --}}
            <input type="hidden" name="status" value="inativo">
            <input class="form-check-input" type="checkbox" name="status" value="ativo" @checked(old('status', $automacao->status->value ?? 'ativo') === 'ativo')>
        </div>
    </div>

</div>
