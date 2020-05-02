<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // metodo que retrona la vista login
    public function showLoginForm(){
        // retorna la vista que esta dentro de la carpeta auth y la vista login
        return view('auth.login');
    }

    // permite verificar las credenciales del usuario para poder acceder al sistema
    public function login(Request $request){
        $this->validateLogin($request) ;

        // verifica si el usuario y el password son correctos
        // mi propiedad usuario de mi clase user va ser igual a mi propiedad usuario del objeto request
        // mi propiedad password de mi clase user va ser igual a mi propiedad password del objeto request
        // ademas la condicion de esa fila sea igual 1
        if (Auth::attempt(['usuario' => $request->usuario, 'password' => $request->password, 'condicion'=>1])){
            // si todo es correcto redirecciona a la ruta main
            return redirect()->route('main');
        }
        // si ocurre un error en la verificacion regresa a donde se encontraba
        // withErrors()especifica el error de acceso
        return back()
        // retorna el error de autenticacion
        ->withErrors(['usuario' => trans('auth.failed')])

        // retorna lo que el usuario escribio en el imput usuario
        ->withInput(request(['usuario']));
    }

    protected function validateLogin(Request $request){
         // valida la petecion de inico de sesion del usuario
         $this->validate($request,[
            // valida las propiedades ademas deben de ser obligatorios
            'usuario' => 'required|string',
            'password' => 'required|string'
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/');

    }
}
