<!-- Botão hamburguer (visível apenas no mobile) -->
<nav class="navbar bg-light d-md-none px-3">
    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenuMobile">
        <i class="bi bi-list"></i> Menu
    </button>
</nav>
<!-- Sidebar fixo no desktop -->
<div class="col-md-2 bg-light vh-100 p-3 border-end d-none d-md-block">
    <ul class="nav flex-column">
        <li class="nav-item mb-2"><a class="nav-link" href="/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="/deposit"><i class="bi bi-piggy-bank"></i> Depósito</a></li>
        <li class="nav-item mb-2"><a class="nav-link" href="/transfer"><i class="bi bi-arrow-left-right"></i> Transferência</a></li>
    </ul>
</div>

<!-- Sidebar offcanvas para mobile -->
<div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="sidebarMenuMobile">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="nav flex-column p-3">
            <li class="nav-item mb-2"><a class="nav-link" href="/dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li class="nav-item mb-2"><a class="nav-link" href="/deposit"><i class="bi bi-piggy-bank"></i> Depósito</a></li>
            <li class="nav-item mb-2"><a class="nav-link" href="/transfer"><i class="bi bi-arrow-left-right"></i> Transferência</a></li>
        </ul>
    </div>
</div>
