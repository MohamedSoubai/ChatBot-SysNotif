<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon me-2">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Sanitaire Ghandi" class="img-fluid" style="max-height: 2.5rem;">
        </div>
        <div class="sidebar-brand-text text-gold fw-bold">Sanitaire Ghandi <sup>2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0 ">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item @if(request()->routeIs('dashboard')) active @endif">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt text-gold"></i>
            <span class="text-white">Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-2 mx-3">

    <!-- Heading -->
    <div class="sidebar-heading text-white px-3 mt-3 mb-2">
        Gestion
    </div>

    <!-- Nav Item - Clients -->
    <li class="nav-item @if(request()->routeIs('clients.*')) active @endif">
        <a class="nav-link" href="{{ route('clients.index') }}">
            <i class="fas fa-fw fa-users text-gold"></i>
            <span class="text-white">Clients</span>
        </a>
    </li>

    <!-- Nav Item - Factures -->
    <li class="nav-item @if(request()->routeIs('factures.*')) active @endif">
        <a class="nav-link" href="{{ route('factures.index') }}">
            <i class="fas fa-fw fa-file-invoice-dollar text-gold"></i>
            <span class="text-white">Factures</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider my-2 mx-3">

    <!-- Heading -->
    <div class="sidebar-heading text-white px-3 mt-3 mb-2">
        Utilisateur
    </div>

    <!-- Nav Item - Profile -->
    <li class="nav-item @if(request()->routeIs('profile')) active @endif">
        <a class="nav-link" href="{{ route('profile') }}">
            <i class="fas fa-fw fa-user text-gold"></i>
            <span class="text-white">Profile</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block my-2 mx-3">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline px-3">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
