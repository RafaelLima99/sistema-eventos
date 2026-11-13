@extends('layouts.app')

@section('title', 'Detalhes da mensagem de WhatsApp')

@section('content')
<div class="page-title d-flex align-items-start gap-3 flex-wrap">
    <div>
        <h2>{{ $whatsappMensagem->titulo }}</h2>
    </div>
    <div class="actions ms-auto d-flex flex-wrap">
        <a href="{{ route('whatsapp-mensagens.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Mensagens</a>
    </div>
</div>

<div class="d-flex flex-wrap align-items-center gap-2 mb-3">
    <span class="chan-ic wa"><i class="bi bi-whatsapp"></i></span>
    @if ($template)
        <span class="badge-status {{ $template['status']->badgeClass() }}"><span class="b-dot"></span>Template {{ mb_strtolower($template['status']->label()) }}</span>
    @endif
    <div class="ms-auto d-flex flex-wrap gap-2">
        <a href="{{ route('whatsapp-mensagens.edit', $whatsappMensagem) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i>Editar</a>
    </div>
</div>

<div class="d-flex flex-column gap-3">

    <div class="card">
        <div class="card-head"><h3>Dados no sistema</h3></div>
        <div class="card-pad">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nome no sistema</label>
                    <input class="form-control" value="{{ $whatsappMensagem->titulo }}" disabled>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-head">
            <h3>Template da Meta</h3>
            <div class="right"><span class="sub">somente leitura</span></div>
        </div>
        <div class="card-pad">
            <div class="note-box mb-3">
                <i class="bi bi-info-circle flex-shrink-0"></i>
                <span>O conteúdo é criado e aprovado dentro da <b>Meta (WhatsApp Manager)</b>. Aqui você só faz o vínculo das variáveis com os campos do sistema.</span>
            </div>
            @if ($template)
                <dl class="kv" style="grid-template-columns:170px 1fr">
                    <dt>Nome do template</dt><dd class="mono">{{ $template['nome'] }}</dd>
                    <dt>Categoria</dt><dd>{{ $template['categoria'] }}</dd>
                    <dt>Idioma</dt><dd><span class="mono">{{ $template['idioma'] }}</span></dd>
                    <dt>Status na Meta</dt><dd><span class="badge-status {{ $template['status']->badgeClass() }}"><span class="b-dot"></span>{{ $template['status']->label() }}</span></dd>
                    <dt>Qualidade</dt><dd>{{ $template['qualidade'] ?? '—' }}</dd>
                    <dt>ID do template</dt><dd class="mono">{{ $template['id'] }}</dd>
                </dl>
                <div class="divider"></div>
                <label class="form-label">Conteúdo aprovado</label>
                <div class="tpl-body">{{ $template['corpo'] }}</div>
                <p class="form-hint mt-2">Precisa mudar o texto? Crie uma nova versão do template no WhatsApp Manager e aguarde a aprovação da Meta.</p>
            @else
                <dl class="kv" style="grid-template-columns:170px 1fr">
                    <dt>Nome do template</dt><dd class="mono">{{ $whatsappMensagem->nome_template_meta }}</dd>
                </dl>
                <p class="form-hint mt-2">Este template não foi encontrado na Meta. Edite a mensagem e escolha outro.</p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-head"><h3>Vínculo das variáveis</h3></div>
        <div class="card-pad">
            <div class="border rounded-3 px-3 py-1">
                @forelse ($whatsappMensagem->variaveis_template ?? [] as $posicao => $campo)
                    <div class="vm-row d-flex align-items-center gap-2">
                        <span class="mono flex-shrink-0">&#123;&#123;{{ $posicao }}&#125;&#125;</span><i class="bi bi-arrow-right"></i>
                        <span>{{ \App\Models\WhatsappMensagem::CAMPOS_DO_SISTEMA[$campo] ?? '—' }}</span>
                    </div>
                @empty
                    <div class="vm-row text-muted">Nenhuma variável vinculada.</div>
                @endforelse
            </div>
            <div class="form-hint">O WhatsApp usa variáveis numeradas (<span class="mono">&#123;&#123;1&#125;&#125;</span>, <span class="mono">&#123;&#123;2&#125;&#125;</span>…). Cada posição é ligada a um campo do sistema — é isso que preenche a mensagem no envio.</div>
        </div>
    </div>

</div>
@endsection
