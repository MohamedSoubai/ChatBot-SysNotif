{{-- resources/views/partials/sidebar.blade.php --}}
{{-- Barre latérale principale, animations CSS/JS incluses --}}

<aside id="sidebar" class="sidebar">
    {{-- <div class="brand">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-logo" />
            <span class="brand-text">MonApp</span>
        </a>
        <button id="sidebar-toggle" class="sidebar-toggle">
            <span class="toggle-icon"></span>
        </button>
    </div> --}}

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ route('clients.index') }}" class="nav-link @if(request()->routeIs('clients.*')) active @endif">
                    <i class="icon-users"></i>
                    <span class="link-text">Clients</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('factures.index') }}" class="nav-link @if(request()->routeIs('factures.*')) active @endif">
                    <i class="icon-invoice"></i>
                    <span class="link-text">Factures</span>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link @if(request()->routeIs('home')) active @endif">
                    <i class="icon-dashboard"></i>
                    <span class="link-text">Tableau de bord</span>
                </a>
            </li> --}}
            {{-- Ajoutez ici d’autres liens si nécessaire --}}
        </ul>
    </nav>

    <div class="sidebar-footer">
        {{-- <div class="user-info">
            <img src="{{ asset('images/avatar.png') }}" alt="Avatar" class="user-avatar" />
            <span class="user-name">{{ Auth::user()->name ?? 'Invité' }}</span>
        </div>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="logout-link">
            <i class="icon-logout"></i>
            <span>Se déconnecter</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form> --}}
    </div>
</aside>
