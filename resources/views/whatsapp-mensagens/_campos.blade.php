{{-- Campos do formulário de mensagem de WhatsApp. Usado por create e edit. $whatsappMensagem só existe na edição. --}}
@php
    // Template que já vem marcado ao abrir a página: o que a pessoa tinha escolhido antes de um erro de validação
    // (o formulário volta com a escolha mantida) ou, na edição, o que já estava salvo. Só vale na hora de montar a página.
    $templateEscolhido = old('nome_template_meta', $whatsappMensagem->nome_template_meta ?? null);
    $maximoDeVariaveis = max(array_column($templates, 'total_variaveis'));
@endphp

<div class="row g-3">

    <div>
        <div class="note-box">
            <i class="bi bi-info-circle flex-shrink-0"></i>
            <span>Templates de WhatsApp são criados e aprovados na <b>Meta (WhatsApp Manager)</b>. Aqui você escolhe um template <b>aprovado</b>, dá um nome interno e faz o vínculo das variáveis.</span>
        </div>
    </div>

    <div>
        <label class="form-label">Nome no sistema</label>
        <input name="titulo" value="{{ old('titulo', $whatsappMensagem->titulo ?? '') }}" class="form-control @error('titulo') is-invalid @enderror" placeholder="Ex.: Confirmação de inscrição · WhatsApp">
        @error('titulo')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label class="form-label">Template aprovado</label>
        <div class="search-inline mb-2">
            <i class="bi bi-search"></i>
            <input type="text" id="buscaTemplate" placeholder="Buscar template...">
        </div>
        <div class="tpl-pick">
            @foreach ($templates as $template)
                @php($estaSelecionado = $templateEscolhido === $template['nome'])
                <label class="tpl-opt {{ $estaSelecionado ? 'on' : '' }}">
                    <input type="radio" name="nome_template_meta" value="{{ $template['nome'] }}" @checked($estaSelecionado)>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="tp-name mono">{{ $template['nome'] }}</span>
                            <span class="cat {{ $template['categoria_classe'] }}">{{ $template['categoria'] }}</span>
                        </div>
                        <div class="tp-body">{{ $template['corpo'] }}</div>
                    </div>
                </label>
            @endforeach
        </div>
        @error('nome_template_meta')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label class="form-label">Vínculo das variáveis <span class="text-muted2 fw-normal">(preencha só as variáveis que constam no template selecionado)</span></label>
        <div class="border rounded-3 px-3 py-1">
            {{-- Uma linha para cada posição do template com mais variáveis. --}}
            @for ($posicao = 1; $posicao <= $maximoDeVariaveis; $posicao++)
                {{-- O que a pessoa tinha escolhido antes de um erro, ou o campo já salvo na edição. --}}
                @php($campoEscolhido = old('variaveis_template.'.$posicao, $whatsappMensagem->variaveis_template[$posicao] ?? null))
                <div class="vm-row">
                    <div class="row g-2 align-items-center">
                        {{-- &#123; e &#125; são "{" e "}" escritos em HTML, para o Blade não confundir com o código dele. --}}
                        <div class="col-auto mono">&#123;&#123;{{ $posicao }}&#125;&#125;</div>
                        <div class="col-auto"><i class="bi bi-arrow-right"></i></div>
                        <div class="col">
                            <select name="variaveis_template[{{ $posicao }}]" class="form-select @error('variaveis_template.'.$posicao) is-invalid @enderror">
                                <option value="">Escolha um campo...</option>
                                @foreach (\App\Models\WhatsappMensagem::CAMPOS_DO_SISTEMA as $campo => $nomeDoCampo)
                                    <option value="{{ $campo }}" @selected($campoEscolhido === $campo)>{{ $nomeDoCampo }}</option>
                                @endforeach
                            </select>
                            @error('variaveis_template.'.$posicao)
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

</div>
