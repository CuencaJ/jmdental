<header id="header" class="header sticky-top">

    {{-- Topbar azul --}}
    <div class="topbar d-flex align-items-center">
        <div class="container d-flex justify-content-center justify-content-md-between">
            {{-- Información de contacto – IZQUIERDA --}}
            <div class="contact-info d-flex align-items-center">
                <i class="bi bi-envelope d-flex align-items-center">
                    <a href="mailto:jmdental@gmail.com">jmdental@gmail.com</a>
                </i>
                <i class="bi bi-phone d-flex align-items-center ms-4">
                    <span>+593 99 999 9999</span>
                </i>
            </div>

            {{-- Redes sociales – DERECHA --}}
            <div class="social-links d-none d-md-flex align-items-center">
                <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>
        </div>
    </div>
    <!-- End Top Bar -->

    {{-- Barra principal blanca --}}
    <div class="branding d-flex align-items-center">
        <div class="container position-relative d-flex align-items-center justify-content-between">

            {{-- Logo / nombre --}}
            <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
                <h1 class="sitename">JM Dental</h1>
            </a>

            {{-- Menú --}}
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="{{ url('/') }}#hero" class="active">Inicio</a></li>
                    <li><a href="{{ url('/') }}#about">Nosotros</a></li>
                    <li><a href="{{ url('/') }}#appointment">Reserva</a></li>
                    <li><a href="{{ url('/') }}#doctors">Doctores</a></li>
                    <li><a href="{{ url('/') }}#gallery">Galería</a></li>
                    <li><a href="{{ url('/') }}#contact">Contacto</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            {{-- Botón derecho (Login / Registrarse) --}}
            @php
                $route = request()->route() ? request()->route()->getName() : null;
            @endphp

            @if ($route === 'login')
                {{-- Si estoy en /login, muestro Registrarse --}}
                @if (Route::has('register'))
                    <a class="cta-btn d-none d-sm-block" href="{{ route('register') }}">
                        Registrarse
                    </a>
                @endif
            @elseif ($route === 'register')
                {{-- Si estoy en /register, muestro Iniciar sesión --}}
                @if (Route::has('login'))
                    <a class="cta-btn d-none d-sm-block" href="{{ route('login') }}">
                        Iniciar sesión
                    </a>
                @endif
            @else
                {{-- En el resto del sitio, solo Login como en la home --}}
                @if (Route::has('login'))
                    <a class="cta-btn d-none d-sm-block" href="{{ route('login') }}">
                        Login
                    </a>
                @endif
            @endif

        </div>
    </div>

</header>
