<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>JM Dental</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{url('plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('dist/css/adminlte.min.css')}}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- jQuery -->
  <script src="{{url('plugins/jquery/jquery.min.js')}}"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- DataTables -->
  <link rel="stylesheet" href="{{url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
  <link rel="stylesheet" href="{{url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">

</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{url('/admin')}}" class="nav-link">Sistema de Deontología JM Dental</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link d-flex justify-content-center align-items-center">
      <span class="brand-text font-weight-light">JM Dental</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-center align-items-center">
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas bi bi-people-fill"></i>
                <p>
                  Usuarios
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{url('admin/users/create')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Crear Usuario</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('admin/users')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lists de usuarios</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas bi bi-person-circle"></i>
                <p>
                  Secretaias
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{url('admin/secretary/create')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Crear Secretaria</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('admin/secretary')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lista de secretarias</p>
                  </a>
                </li>
              </ul>
            </li>

          <!--@can('admin.patient.index')
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas bi bi-person-fill-check"></i>
                <p>
                  Patients
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{url('admin/patient/create')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Patient Creation</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('admin/patient')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>List of Patients</p>
                  </a>
                </li>
              </ul>
            </li>
          @endcan

          
          @can('admin.office.index')
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas bi bi-building-add"></i>
                <p>
                  Consulting rooms
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{url('admin/office/create')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Office Creation</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('admin/office')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>List of Office</p>
                  </a>
                </li>
              </ul>
            </li>
          @endcan

        -->
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas bi bi-person-heart"></i>
                <p>
                  Doctores
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{url('admin/doctors/create')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Crear Doctor</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('admin/doctors')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Lista de doctores</p>
                  </a>
                </li>
              </ul>
            </li>

<!---
          @can('admin.schedule.index')
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon fas bi bi-calendar2-week-fill"></i>
                <p>
                  Schedule
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{url('admin/schedule/create')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Schedule Creation</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('admin/schedule')}}" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>List of Schedule</p>
                  </a>
                </li>
              </ul>
            </li>
          @endcan-->

          <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link"
              onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"
            >
              <i class="nav-icon fas bi bi-door-closed"></i>
              <p>
                Cerrar Sesión
              </p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

<!--Condicion para mostrar el mensaje con js-->
  @if(($message = Session::get('message')) && ($icon = Session::get('icon')))
    <script>
      Swal.fire({
      position: "top-end",
      icon: "{{$icon}}",
      title: "{{$message}}",
      showConfirmButton: false,
      timer: 1500
      });
    </script>
  @endif

  <div class="content-wrapper">
    <br>
    <div class="container">
        @yield('content')
    </div>
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      Anything you want
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2025 <a href="https://adminlte.io">JM Dental</a>.</strong>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->


<!-- Bootstrap 4 -->
<script src="{{url('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{url('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{url('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{url('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{url('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{url('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

<!-- AdminLTE App -->
<script src="{{url('dist/js/adminlte.min.js')}}"></script>
</body>
</html>

 