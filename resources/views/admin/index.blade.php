@extends('layouts.admin')
@section('content')
  <div class="row">
    <h1><b>Welcome:</b> {{Auth::user()->name}} / <b>Rol:</b> {{Auth::user()->roles->pluck('name')->first()}}</h1>
  </div>
  <hr>
  <div class="row">
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{$total_user}}</h3>
            <p>Usuarios</p>
          </div>
          <div class="icon">
            <i class="fas bi bi-file-person"></i>
          </div>
          <a href="{{url('admin/users')}}" class="small-box-footer">Más información <i class="fas bi bi-file-person"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
          <div class="inner">
            <h3>0</h3>
            <p>Secretarios</p>
          </div>
          <div class="icon">
            <i class="fas bi bi-person-circle"></i>
          </div>
          <a href="{{url('admin/secretary')}}" class="small-box-footer">Más información <i class="fas bi bi-person-circle"></i></a>
        </div>
      </div>
    
    <!--@can('admin.patient.index')
      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>{{$total_patients}}</h3>
            <p>Pacientes</p>
          </div>
          <div class="icon">
            <i class="fas bi bi-person-fill-check"></i>
          </div>
          <a href="{{url('admin/patient')}}" class="small-box-footer">Más información <i class="fas bi bi-person-fill-check"></i></a>
        </div>
      </div>
    @endcan

    @can('admin.office.index')
      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{$total_office}}</h3>
            <p>Laboratorios</p>
          </div>
          <div class="icon">
            <i class="fas bi bi-building-add"></i>
          </div>
          <a href="{{url('admin/office')}}" class="small-box-footer">Más información <i class="fas bi bi-building-add"></i></a>
        </div>
      </div>
    @endcan-->

      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>0</h3>
            <p>Doctores</p>
          </div>
          <div class="icon">
            <i class="fas bi bi-person-heart"></i>
          </div>
          <a href="{{url('admin/doctors')}}" class="small-box-footer">Más información <i class="fas bi bi-person-heart"></i></a>
        </div>
      </div>

    <!--@can('admin.schedule.index')
      <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
          <div class="inner">
            <h3>{{$total_horarios}}</h3>
            <p>Calendario</p>
          </div>
          <div class="icon">
            <i class="fas bi bi-calendar2-week-fill"></i>
          </div>
          <a href="{{url('admin/schedule')}}" class="small-box-footer">Más información <i class="fas bi bi-calendar2-week-fill"></i></a>
        </div>
      </div>
    @endcan-->

  </div>
@endsection