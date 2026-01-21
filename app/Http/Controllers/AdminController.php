<?php

namespace App\Http\Controllers;

//use App\Models\Doctor;
//use App\Models\Secretaria;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
//crearemos una funcion que retorne una vista, sera nuestra funcion principal
    public function index(){
        $total_user = User::count();
        //$total_secretarias = Secretaria::count();
        //$total_doctors = Doctor::count();
        //ingresamos a la carpeta view para ingresar a la carpeta admin y al archivo
        //de esta forma retornaremos esta vista del controlador que hemos creado
        return view('admin.index',compact(
            'total_user' 
            //'total_secretarias', 
            //'total_doctors'
        ));
    }
}
