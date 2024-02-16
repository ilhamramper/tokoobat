<style>
    .nav-item,
    .dropdown-item {
        color: #F8FFFF;
    }
</style>

<nav class="navbar navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <div class="justify-content-start">
            @if (auth()->check())
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            @endif
            <a class="navbar-brand ms-2"
                href="{{ Auth::check() ? (Auth::user()->role == 'pelanggan' ? route('order') : route('home')) : route('home') }}">Aplikasi
                Toko Obat</a>
        </div>
        <div class="d-flex mx-2">
            <!-- Authentication Links -->
            @guest
                @if (Route::has('login'))
                    <div class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </div>
                @endif

                @if (Route::has('register'))
                    <div class="nav-item mx-2">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </div>
                @endif
            @else
                <div class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->username }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-end text-end" aria-labelledby="navbarDropdown"
                        style="background-color: #3d444b">
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Keluar') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            @endguest
        </div>
        <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Aplikasi Toko Obat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <style>
                        .nav-link {
                            font-weight: 500;
                        }

                        .nav-link.active {
                            font-weight: bold;
                        }
                    </style>
                    @if (Auth::check())
                        @if (Auth::user()->role == 'pelanggan')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs(['order']) ? 'active' : '' }}"
                                    href="{{ route('order') }}">Pesan Obat</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs(['riwayat.order']) ? 'active' : '' }}"
                                    href="{{ route('riwayat.order', ['id' => auth()->user()->id_pelanggan]) }}">Riwayat Pesanan</a>
                            </li>
                        @elseif(Auth::user()->role == 'petugas')
                            <li class="nav-item">
                                <a class="nav-link  {{ request()->routeIs(['transaksi']) ? 'active' : '' }}"
                                    href="{{ route('transaksi') }}">Data Pesanan</a>
                            </li>
                        @elseif(Auth::user()->role == 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs(['home', 'create.users', 'edit.users']) ? 'active' : '' }}"
                                    href="{{ route('home') }}">Data User</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs(['kategori', 'create.kategori']) ? 'active' : '' }}"
                                    href="{{ route('kategori') }}">Data Kategori Obat</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs(['obat', 'create.obat', 'edit.obat']) ? 'active' : '' }}"
                                    href="{{ route('obat') }}">Data Obat</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link  {{ request()->routeIs(['transaksi']) ? 'active' : '' }}"
                                    href="{{ route('transaksi') }}">Data Pesanan</a>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
