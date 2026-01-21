@extends('layouts.admin')
@section('content')
    <div class="row">
        <h1>Usuario: {{$usuario->name}}</h1>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6 ">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">¿Esta seguro que quiere eliminar este usuario?</h3>
                </div>
                <div class="card-body">
                    <form action="{{url('/admin/users', $usuario->id)}}" method="POST">
                    <!--Token de seguridad en laravel-->
                        @csrf
                        @method('DELETE')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Nombres</label>
                                    <input type="text" value="{{$usuario->name}}" name="name" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Correo electrónico</label>
                                    <input type="email" value="{{$usuario->email}}" name="email" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="{{url('admin/users')}}" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection