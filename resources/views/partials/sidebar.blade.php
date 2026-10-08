<aside class="sidebar d-flex flex-column position-fixed top-0 start-0 bottom-0">
    <div class="brand d-flex align-items-center flex-shrink-0">
        <div class="logo d-grid"><i class="bi bi-graph-up-arrow"></i></div>
        <div><b>Gestão de Eventos</b></div>
    </div>
    <div class="nav-scroll flex-grow-1 pt-3 pb-4">
        <div class="nav-group">
            <p>Principal</p>
            <a class="side-link {{ request()->routeIs('dashboard') ? 'active' : '' }} d-flex align-items-center" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2"></i><span>Dashboard</span></a>
            <a class="side-link {{ request()->routeIs('eventos.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('eventos.index') }}"><i class="bi bi-calendar-event"></i><span>Eventos</span></a>
        </div>
        <div class="nav-group">
            <p>Participantes</p>
            <a class="side-link {{ request()->routeIs('inscritos.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('inscritos.index') }}"><i class="bi bi-people"></i><span>Inscritos</span></a>
            <a class="side-link {{ request()->routeIs('contatos.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('contatos.index') }}"><i class="bi bi-person-lines-fill"></i><span>Contatos</span></a>
            <a class="side-link {{ request()->routeIs('presenca.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('presenca.index') }}"><i class="bi bi-qr-code-scan"></i><span>Controle de Presença</span></a>
        </div>
        <div class="nav-group">
            <p>Comunicação</p>
            <a class="side-link {{ request()->routeIs('automacoes.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('automacoes.index') }}"><i class="bi bi-robot"></i><span>Automações</span></a>
            <a class="side-link {{ request()->routeIs('whatsapp-mensagens.*', 'email-mensagens.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('whatsapp-mensagens.index') }}"><i class="bi bi-chat-square-text"></i><span>Mensagens</span></a>
            <a class="side-link {{ request()->routeIs('disparos.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('disparos.index') }}"><i class="bi bi-send"></i><span>Disparos</span></a>
        </div>
        <div class="nav-group">
            <p>Sistema</p>
            <a class="side-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('usuarios.index') }}"><i class="bi bi-person-badge"></i><span>Usuários</span></a>
            <a class="side-link {{ request()->routeIs('configuracoes.*') ? 'active' : '' }} d-flex align-items-center" href="{{ route('configuracoes.index') }}"><i class="bi bi-gear"></i><span>Configurações</span></a>
        </div>
    </div>
</aside>
