@extends('layouts.app')

@section('title', 'Detalhes da mensagem de WhatsApp')

@section('content')
        <div class="page-title d-flex align-items-start gap-3 flex-wrap">
            <div>
                <h2>Confirmação de inscrição · WhatsApp</h2>
            </div>
            <div class="actions ms-auto d-flex flex-wrap">
                <a href="{{ route('whatsapp-mensagens.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i>Voltar para Mensagens</a>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
            <span class="chan-ic wa"><i class="bi bi-whatsapp"></i></span>
            <span class="badge-status bs-green"><span class="b-dot"></span>Template aprovado</span>
            <div class="ms-auto d-flex flex-wrap gap-2">
                <a href="#" class="btn btn-outline-secondary btn-sm"><i class="bi bi-files"></i>Duplicar</a>
                <a href="{{ route('whatsapp-mensagens.edit', 1) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i>Editar</a>
            </div>
        </div>

        <div class="d-flex flex-column gap-3">

            <div class="card">
                <div class="card-head"><h3>Dados no sistema</h3></div>
                <div class="card-pad">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nome no sistema</label>
                            <input class="form-control" value="Confirmação de inscrição · WhatsApp" disabled>
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
                    <dl class="kv" style="grid-template-columns:170px 1fr">
                        <dt>Nome do template</dt><dd class="mono">confirmacao_inscricao</dd>
                        <dt>Categoria</dt><dd>Utilidade (Utility)</dd>
                        <dt>Idioma</dt><dd>Português (Brasil) · <span class="mono">pt_BR</span></dd>
                        <dt>Status na Meta</dt><dd><span class="badge-status bs-green"><span class="b-dot"></span>Aprovado</span></dd>
                        <dt>Qualidade</dt><dd><span class="badge-status bs-green"><span class="b-dot"></span>Alta</span></dd>
                        <dt>ID do template</dt><dd class="mono">1180239776450912</dd>
                        <dt>WABA</dt><dd class="mono">Eventos CE · 3920…1174</dd>
                        <dt>Última sincronização</dt><dd>hoje, 08:40</dd>
                    </dl>
                    <div class="divider"></div>
                    <label class="form-label">Conteúdo aprovado</label>
                    <div class="tpl-body">Olá @{{1}}! 🎉

Sua inscrição no *@{{2}}* foi confirmada.

📅 @{{3}} às @{{4}}
📍 @{{5}}

Guarde este contato. Enviaremos os próximos avisos por aqui.</div>
                    <p class="form-hint mt-2">Precisa mudar o texto? Crie uma nova versão do template no WhatsApp Manager e aguarde a aprovação da Meta.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-head"><h3>Vínculo das variáveis</h3></div>
                <div class="card-pad">
                    <div class="border rounded-3 px-3 py-1">
                        <div class="vm-row d-flex align-items-center gap-2">
                            <span class="mono flex-shrink-0">@{{1}}</span><i class="bi bi-arrow-right"></i>
                            <select class="form-select flex-grow-1"><option selected>Nome do inscrito</option><option>Responsável</option><option>Nome do evento</option></select>
                        </div>
                        <div class="vm-row d-flex align-items-center gap-2">
                            <span class="mono flex-shrink-0">@{{2}}</span><i class="bi bi-arrow-right"></i>
                            <select class="form-select flex-grow-1"><option>Nome do inscrito</option><option selected>Nome do evento</option><option>Local</option></select>
                        </div>
                        <div class="vm-row d-flex align-items-center gap-2">
                            <span class="mono flex-shrink-0">@{{3}}</span><i class="bi bi-arrow-right"></i>
                            <select class="form-select flex-grow-1"><option selected>Data do evento</option><option>Horário</option></select>
                        </div>
                        <div class="vm-row d-flex align-items-center gap-2">
                            <span class="mono flex-shrink-0">@{{4}}</span><i class="bi bi-arrow-right"></i>
                            <select class="form-select flex-grow-1"><option>Data do evento</option><option selected>Horário</option></select>
                        </div>
                        <div class="vm-row d-flex align-items-center gap-2">
                            <span class="mono flex-shrink-0">@{{5}}</span><i class="bi bi-arrow-right"></i>
                            <select class="form-select flex-grow-1"><option selected>Local + cidade</option><option>Link</option></select>
                        </div>
                    </div>
                    <div class="form-hint">O WhatsApp usa variáveis numeradas (<span class="mono">@{{1}}</span>, <span class="mono">@{{2}}</span>…). Vincule cada posição a um campo do sistema — é isso que preenche a mensagem no envio.</div>
                </div>
            </div>

        </div>
@endsection
