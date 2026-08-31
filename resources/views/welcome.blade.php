<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>JM Dental</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header sticky-top">
    <div class="branding d-flex align-items-center">
      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto">
          <h1 class="sitename">JM Dental</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#hero" class="active">Inicio</a></li>
            <li><a href="#about">Nosotros</a></li>
            <li><a href="#appointment">Reserva</a></li>
            <li><a href="#contact">Contacto</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="cta-btn d-none d-sm-block" href="{{ route('login') }}">Login</a>
      </div>
    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section light-background">
      <img src="{{ asset('assets/img/pagina-web-clinica-dental.jpg') }}" alt="" data-aos="fade-in">
      <div class="container position-relative">
        <div class="welcome position-relative" data-aos="fade-down" data-aos-delay="100">
          <h2>BIENVENIDO A JM DENTAL</h2>
          <p>Cuidamos tu sonrisa con atención profesional y tecnología moderna.</p>
        </div>
        <div class="content row gy-4">
          <div class="col-lg-4 d-flex align-items-stretch">
            <div class="why-box" data-aos="zoom-out" data-aos-delay="200">
              <h3>Agenda tu cita odontológica</h3>
              <div class="text-center">
                <a href="#appointment" class="more-btn"><span>Agendar ahora</span> <i class="bi bi-chevron-right"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-8 d-flex align-items-stretch">
            <div class="d-flex flex-column justify-content-center">
              <div class="row gy-4">
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="300">
                    <i class="bi bi-shield-check"></i>
                    <h4>Atención rápida y segura</h4>
                    <p>Revisión completa, diagnóstico y plan de tratamiento personalizado para cuidar tu salud bucal.</p>
                  </div>
                </div>
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="400">
                    <i class="bi bi-gear-wide-connected"></i>
                    <h4>Tecnología moderna</h4>
                    <p>Equipos actualizados y diagnósticos precisos para un tratamiento más cómodo y confiable.</p>
                  </div>
                </div>
                <div class="col-xl-4 d-flex align-items-stretch">
                  <div class="icon-box" data-aos="zoom-out" data-aos-delay="500">
                    <i class="bi bi-journal-medical"></i>
                    <h4>Historial clínico</h4>
                    <p>Accede a tu historial, procedimientos realizados y recomendaciones personalizadas.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about section">
      <div class="container">
        <div class="row gy-4 gx-5">
          <div class="col-lg-6 position-relative align-self-start" data-aos="fade-up" data-aos-delay="200">
            <img src="{{ asset('assets/img/Odontologia-PUCE.jpg') }}" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
            <h3>Nosotros</h3>
            <p>En JM Dental nos especializamos en brindar atención odontológica de calidad, con un enfoque humano y profesional. Nuestro objetivo es cuidar tu salud bucal mediante diagnósticos precisos, tratamientos modernos y un acompañamiento personalizado.</p>
            <ul>
              <li>
                <i class="bi bi-person-check"></i>
                <div>
                  <h5>Odontólogos certificados</h5>
                  <p>Contamos con especialistas en diversas áreas para ofrecerte un plan de tratamiento completo.</p>
                </div>
              </li>
              <li>
                <i class="bi bi-shield-check"></i>
                <div>
                  <h5>Ambiente cómodo y seguro</h5>
                  <p>Creamos espacios pensados para reducir la ansiedad dental y mejorar tu experiencia.</p>
                </div>
              </li>
              <li>
                <i class="bi bi-calendar-event"></i>
                <div>
                  <h5>Facilidad para agendar</h5>
                  <p>Reserva tus citas en línea y recibe recordatorios automáticos.</p>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Appointment Section -->
    <section id="appointment" class="appointment section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Agenda tu Cita</h2>
        <p>Para reservar una cita necesitas tener una cuenta en nuestro sistema.</p>
      </div>
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
          <div class="col-lg-6 text-center">
            <div class="alert alert-warning p-4 rounded-3" role="alert">
              <i class="bi bi-lock-fill fs-3 mb-3 d-block"></i>
              <h5 class="fw-bold">Inicia sesión para reservar</h5>
              <p class="mb-3">Para agendar una cita debes iniciar sesión o crear una cuenta.</p>
              <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('login') }}" class="btn btn-primary px-4">
                  <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </a>
                <a href="{{ route('registro') }}" class="btn btn-outline-primary px-4">
                  <i class="bi bi-person-plus me-2"></i>Crear Cuenta
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Contacto</h2>
        <p>¿Tienes alguna pregunta? Contáctanos.</p>
      </div>
      <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
        <iframe style="border:0; width: 100%; height: 270px;"
          src="https://www.google.com/maps?q=-0.9631651,-80.7076139&hl=es&z=17&output=embed"
          frameborder="0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">
          <div class="col-lg-4">
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Ubicación</h3>
                <p>C.118 y Av.113, Manta, Manabí</p>
              </div>
            </div>
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Llámanos</h3>
                <p>+593 96 710 1552</p>
              </div>
            </div>
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Déjanos un correo</h3>
                <p>jmdental@gmail.com</p>
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="row gy-4" data-aos="fade-up" data-aos-delay="200">

              <div class="col-md-6">
                <a href="https://wa.me/593967101552?text=Hola%20JM%20Dental%2C%20quisiera%20informaci%C3%B3n%20sobre%20sus%20servicios."
                   target="_blank" rel="noopener"
                   class="d-block text-center p-4 rounded-3 h-100 text-decoration-none"
                   style="border:1px solid #dee2e6; transition:all .2s;">
                  <i class="bi bi-whatsapp" style="font-size:2.5rem; color:#25D366;"></i>
                  <h4 class="mt-3 mb-2 fw-bold" style="color:#2c4964;">Escríbenos por WhatsApp</h4>
                  <p class="mb-0 text-muted">Respuesta rápida para agendar o resolver dudas.</p>
                </a>
              </div>

              <div class="col-md-6">
                <a href="mailto:jmdental@gmail.com?subject=Consulta%20desde%20la%20web"
                   class="d-block text-center p-4 rounded-3 h-100 text-decoration-none"
                   style="border:1px solid #dee2e6; transition:all .2s;">
                  <i class="bi bi-envelope-fill" style="font-size:2.5rem; color:#1977cc;"></i>
                  <h4 class="mt-3 mb-2 fw-bold" style="color:#2c4964;">Envíanos un correo</h4>
                  <p class="mb-0 text-muted">Para consultas más detalladas o documentación.</p>
                </a>
              </div>

              <div class="col-12 text-center mt-4">
                <p class="text-muted mb-3">¿Quieres agendar una cita en línea?</p>
                <a href="{{ route('login') }}" class="btn btn-primary px-4 me-2">
                  <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </a>
                <a href="{{ route('registro') }}" class="btn btn-outline-primary px-4">
                  <i class="bi bi-person-plus me-2"></i>Crear Cuenta
                </a>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

  </main>

  <footer id="footer" class="footer light-background">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="{{ url('/') }}" class="logo d-flex align-items-center">
            <span class="sitename">JM Dental</span>
          </a>
          <div class="footer-contact pt-3">
            <p>C.118 y Av.113</p>
            <p>Manta, Manabí — Ecuador</p>
            <p class="mt-3"><strong>Teléfono:</strong> <span>+593 96 710 1552</span></p>
            <p><strong>Email:</strong> <span>jmdental@gmail.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href="https://wa.me/593967101552?text=Hola%20JM%20Dental%2C%20quisiera%20informaci%C3%B3n%20sobre%20sus%20servicios."
               target="_blank" rel="noopener" title="Escríbenos por WhatsApp">
              <i class="bi bi-whatsapp"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Enlaces útiles</h4>
          <ul>
            <li><a href="{{ url('/') }}">Inicio</a></li>
            <li><a href="#about">Nosotros</a></li>
            <li><a href="#appointment">Reserva</a></li>
            <li><a href="#contact">Contacto</a></li>
            <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">JM Dental</strong> <span>Todos los derechos reservados</span></p>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

</body>
</html>