<header class="topbar position-sticky top-0 d-flex align-items-center gap-3">
    <label for="sbToggle" class="menu-btn border-0 bg-transparent"><i class="bi bi-list"></i></label>
    <div class="spacer flex-grow-1"></div>
    <a class="icon-btn position-relative d-grid" href="#" title="Notificações"><i class="bi bi-bell"></i><span class="dot position-absolute"></span></a>
    <div class="dropdown">
        <div class="user d-flex align-items-center ms-1 ps-3 pe-0" data-bs-toggle="dropdown">
            <div class="av d-grid">RL</div>
            <div class="who"><b>Rafael Lima</b><small>Admin</small></div>
            <i class="bi bi-chevron-down text-muted2" style="font-size:12px"></i>
        </div>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item py-2" href="{{ route('configuracoes.index') }}"><i class="bi bi-person me-2"></i>Meu perfil</a></li>
            <li><a class="dropdown-item py-2" href="{{ route('configuracoes.index') }}"><i class="bi bi-gear me-2"></i>Configurações</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item py-2" href="{{ route('login') }}"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
        </ul>
    </div>
</header>
