@extends('layouts.app')

@section('title', 'Configurações')

@push('styles')
<style>
.set-nav a { display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:10px; font-size:13px; font-weight:500; color:var(--slate-tx); margin-bottom:2px; }
.set-nav a i { font-size:16px; color:var(--faint); }
.set-nav a:hover { background:var(--slate-bg); }
.set-nav a.active { background:var(--blue-50); color:var(--blue-700); }
.set-nav a.active i { color:var(--blue-600); }
.integ { display:flex; align-items:center; gap:14px; padding:16px; border:1px solid var(--line); border-radius:12px; margin-bottom:12px; }
.integ .ii { width:44px;height:44px;border-radius:11px;display:grid;place-items:center;font-size:20px;color:#fff; }
</style>
@endpush

@section('content')
<div class="page-title">
  <div><h2>Configurações</h2><p>Preferências gerais do sistema e padrões aplicados a novos eventos.</p></div>
  <div class="actions"><a href="#" class="btn btn-primary"><i class="bi bi-check-lg"></i>Salvar alterações</a></div>
</div>

<div class="grid" style="grid-template-columns:240px 1fr;align-items:start">

  <div class="card card-pad set-nav">
    <a class="active" href="#geral"><i class="bi bi-sliders"></i>Geral</a>
    <a href="#marca"><i class="bi bi-palette"></i>Marca</a>
    <a href="#integracoes"><i class="bi bi-plug"></i>Integrações</a>
    <a href="#eventos"><i class="bi bi-calendar-week"></i>Padrões de eventos</a>
    <a href="#equipe"><i class="bi bi-people"></i>Equipe</a>
    <a href="#notif"><i class="bi bi-bell"></i>Notificações</a>
    <a href="#faturamento"><i class="bi bi-credit-card"></i>Faturamento</a>
  </div>

  <div style="display:flex;flex-direction:column;gap:16px">

    <!-- Geral -->
    <div class="card" id="geral">
      <div class="card-head"><h3>Geral</h3></div>
      <div class="card-pad">
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Nome da organização</label><input class="form-control" value="UNU — Eventos"></div>
          <div class="col-md-6"><label class="form-label">E-mail de contato</label><input class="form-control" value="eventos@unu.com.br"></div>
          <div class="col-md-4"><label class="form-label">Fuso horário</label><select class="form-select"><option selected>(GMT-03:00) São Paulo</option><option>(GMT-04:00) Manaus</option></select></div>
          <div class="col-md-4"><label class="form-label">Idioma</label><select class="form-select"><option selected>Português (Brasil)</option><option>English (US)</option></select></div>
          <div class="col-md-4"><label class="form-label">Formato de data</label><select class="form-select"><option selected>DD/MM/AAAA</option><option>AAAA-MM-DD</option></select></div>
        </div>
      </div>
    </div>

    <!-- Marca -->
    <div class="card" id="marca">
      <div class="card-head"><h3>Marca</h3><div class="right"><span class="sub">aplicada em páginas de inscrição e e-mails</span></div></div>
      <div class="card-pad">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Logotipo</label>
            <div style="border:2px dashed var(--line);border-radius:12px;padding:20px;text-align:center;color:var(--muted)">
              <i class="bi bi-image" style="font-size:24px"></i><div style="font-size:12px;margin-top:6px">PNG ou SVG · até 1 MB</div>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label">Cor primária</label>
            <div style="display:flex;gap:10px;align-items:center">
              <span style="width:36px;height:36px;border-radius:9px;background:#2563eb;border:1px solid var(--line)"></span>
              <input class="form-control mono" value="#2563EB" style="max-width:140px">
            </div>
            <label class="form-label" style="margin-top:14px">Cor de destaque</label>
            <div style="display:flex;gap:10px;align-items:center">
              <span style="width:36px;height:36px;border-radius:9px;background:#0b2545;border:1px solid var(--line)"></span>
              <input class="form-control mono" value="#0B2545" style="max-width:140px">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Integrações -->
    <div class="card" id="integracoes">
      <div class="card-head"><h3>Integrações</h3></div>
      <div class="card-pad">
        <div class="integ">
          <div class="ii" style="background:#25d366"><i class="bi bi-whatsapp"></i></div>
          <div style="flex:1"><b style="font-size:13.5px">WhatsApp Business Platform (API oficial da Meta)</b><div class="text-muted2" style="font-size:12px">WABA Eventos CE · +55 11 4210-9922 · 9 templates aprovados · 1 em análise · sincronizado há 6 min</div></div>
          <span class="badge-status bs-green"><span class="b-dot"></span>Conectado</span>
          <button class="btn btn-outline-secondary btn-sm">Gerenciar</button>
        </div>
        <div class="integ">
          <div class="ii" style="background:#2563eb"><i class="bi bi-envelope"></i></div>
          <div style="flex:1"><b style="font-size:13.5px">E-mail transacional</b><div class="text-muted2" style="font-size:12px">Domínio verificado: unu.com.br · SPF/DKIM ok</div></div>
          <span class="badge-status bs-green"><span class="b-dot"></span>Conectado</span>
          <button class="btn btn-outline-secondary btn-sm">Gerenciar</button>
        </div>
        <div class="integ">
          <div class="ii" style="background:#0b2545"><i class="bi bi-credit-card-2-front"></i></div>
          <div style="flex:1"><b style="font-size:13.5px">Gateway de pagamento</b><div class="text-muted2" style="font-size:12px">Conta: UNU Pagamentos · repasse D+2</div></div>
          <span class="badge-status bs-green"><span class="b-dot"></span>Conectado</span>
          <button class="btn btn-outline-secondary btn-sm">Gerenciar</button>
        </div>
        <div class="integ">
          <div class="ii" style="background:#b45309"><i class="bi bi-graph-up"></i></div>
          <div style="flex:1"><b style="font-size:13.5px">Google Analytics / Pixel</b><div class="text-muted2" style="font-size:12px">Rastreamento de conversão nas páginas de inscrição</div></div>
          <span class="badge-status bs-amber"><span class="b-dot"></span>Pendente</span>
          <button class="btn btn-primary btn-sm">Conectar</button>
        </div>
      </div>
    </div>

    <!-- Padrões de eventos -->
    <div class="card" id="eventos">
      <div class="card-head"><h3>Padrões de eventos</h3><div class="right"><span class="sub">aplicados ao criar um novo evento</span></div></div>
      <div class="card-pad">
        <div class="switch-row">
          <div style="flex:1"><b>Ativar comunicação automática por padrão</b><br><small>Cria os 4 gatilhos (15 min, 1 h, véspera, dia do evento) ao publicar</small></div>
          <div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div>
        </div>
        <div class="switch-row">
          <div style="flex:1"><b>Cobrança de não pagantes</b><br><small>Sequência de lembretes de pagamento para inscritos sem pagamento</small></div>
          <div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div>
        </div>
        <div class="switch-row">
          <div style="flex:1"><b>Lista de espera automática</b><br><small>Abre fila quando a capacidade é atingida</small></div>
          <div class="form-check form-switch"><input class="form-check-input" type="checkbox"></div>
        </div>
        <div class="row g-3" style="margin-top:6px">
          <div class="col-md-4"><label class="form-label">Horário padrão de lembrete (véspera)</label><input class="form-control" type="time" value="18:00"></div>
          <div class="col-md-4"><label class="form-label">Horário padrão (dia do evento)</label><input class="form-control" type="time" value="07:30"></div>
          <div class="col-md-4"><label class="form-label">Tipos de ingresso padrão</label><input class="form-control" value="VIP, Inteira, Meia, Cortesia"></div>
        </div>
      </div>
    </div>

    <!-- Equipe -->
    <div class="card" id="equipe">
      <div class="card-head"><h3>Equipe</h3><div class="right"><a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-person-plus"></i>Convidar</a></div></div>
      <div class="table-wrap">
        <table class="data">
          <thead><tr><th>Membro</th><th>E-mail</th><th>Papel</th><th>Último acesso</th><th></th></tr></thead>
          <tbody>
            <tr><td><div class="u-cell"><span class="avatar" style="background:#2563eb">RL</span><b>Rafael Lima</b></div></td><td>ferramentas@unu.com.br</td><td><span class="badge-status bs-blue"><span class="b-dot"></span>Admin</span></td><td>Agora</td><td style="text-align:right"><button class="btn btn-outline-secondary btn-sm"><i class="bi bi-three-dots"></i></button></td></tr>
            <tr><td><div class="u-cell"><span class="avatar" style="background:#0f766e">MC</span><b>Marina Costa</b></div></td><td>marina.costa@unu.com.br</td><td><span class="badge-status bs-slate"><span class="b-dot"></span>Operação</span></td><td>Hoje, 08:20</td><td style="text-align:right"><button class="btn btn-outline-secondary btn-sm"><i class="bi bi-three-dots"></i></button></td></tr>
            <tr><td><div class="u-cell"><span class="avatar" style="background:#7c3aed">BA</span><b>Bruno Alves</b></div></td><td>bruno.alves@unu.com.br</td><td><span class="badge-status bs-slate"><span class="b-dot"></span>Operação</span></td><td>Ontem, 19:44</td><td style="text-align:right"><button class="btn btn-outline-secondary btn-sm"><i class="bi bi-three-dots"></i></button></td></tr>
            <tr><td><div class="u-cell"><span class="avatar" style="background:#db2777">CR</span><b>Camila Rocha</b></div></td><td>camila.rocha@unu.com.br</td><td><span class="badge-status bs-slate"><span class="b-dot"></span>Somente leitura</span></td><td>28/08/2026</td><td style="text-align:right"><button class="btn btn-outline-secondary btn-sm"><i class="bi bi-three-dots"></i></button></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Notificações -->
    <div class="card" id="notif">
      <div class="card-head"><h3>Notificações</h3></div>
      <div class="card-pad">
        <div class="switch-row"><div style="flex:1"><b>Nova inscrição</b><br><small>Resumo diário por e-mail</small></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div></div>
        <div class="switch-row"><div style="flex:1"><b>Pagamento confirmado</b><br><small>Notificação instantânea no painel</small></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div></div>
        <div class="switch-row"><div style="flex:1"><b>Meta de inscrições atingida</b><br><small>Alerta quando o evento chega a 100% da capacidade</small></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div></div>
        <div class="switch-row"><div style="flex:1"><b>Falha em disparo</b><br><small>Aviso imediato se um envio falhar</small></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div></div>
      </div>
    </div>

    <!-- Faturamento -->
    <div class="card" id="faturamento">
      <div class="card-head"><h3>Faturamento</h3></div>
      <div class="card-pad">
        <div class="grid g-3" style="margin-bottom:14px">
          <div class="info-block"><h4>Plano</h4><b style="font-size:16px">Pro</b><div class="text-muted2" style="font-size:12px">Eventos ilimitados · 5.000 mensagens/mês</div></div>
          <div class="info-block"><h4>Uso do mês</h4><b style="font-size:16px">2.520 / 5.000</b><div class="mini-bar" style="margin-top:8px"><span style="width:50%"></span></div></div>
          <div class="info-block"><h4>Próxima cobrança</h4><b style="font-size:16px">01/10/2026</b><div class="text-muted2" style="font-size:12px">R$ 349,00 · cartão final 4421</div></div>
        </div>
        <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-receipt"></i>Ver faturas</button>
        <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-up-circle"></i>Mudar de plano</button>
      </div>
    </div>

    <div style="display:flex;justify-content:flex-end;gap:10px">
      <a href="#" class="btn btn-outline-secondary">Descartar</a>
      <a href="#" class="btn btn-primary"><i class="bi bi-check-lg"></i>Salvar alterações</a>
    </div>

  </div>
</div>
@endsection
