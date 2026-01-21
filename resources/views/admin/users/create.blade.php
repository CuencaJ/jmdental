@extends('layouts.admin')
@section('content')
    <div class="row">
        <h1>Registrar un nuevo usuario</h1>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6 ">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Complete los datos</h3>
                </div>
                <div class="card-body">
                    <form action="{{url('/admin/users/create')}}" method="POST">
                    <!--Token de seguridad en laravel-->
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Nombres</label> <b>*</b>
                                    <input type="text" value="{{old('name')}}" name="name" class="form-control" required>
                                    @error ('name')
                                    <small style="color:red">{{$message}}</small>   
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Correo electrónico</label> <b>*</b>
                                    <input type="email" value="{{old('email')}}" name="email" class="form-control" required>
                                    @error ('email')
                                    <small style="color:red">{{$message}}</small>   
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Contraseña</label> <b>*</b>
                                    <input type="password" name="password" class="form-control" required>
                                    @error ('password')
                                    <small style="color:red">{{$message}}</small>   
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Confirmar contraseña</label> <b>*</b>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                    @error ('password_confirmation')
                                    <small style="color:red">{{$message}}</small>   
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a href="{{url('admin/users')}}" class="btn btn-secondary">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Registrar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection