<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /*public function showLoginForm(){
        return view('auth.login');
    }*/

    public function login(Request $request){
        $credentials = [
            "Nombre_Usuario" => $request->Nombre_Usuario,
            "Contrasena" => Hash::make($request->Contrasena),
        ];

        $remember = true;
        
        dd($credentials);
        if(Auth::attempt($credentials, $remember)){
            $request->session()->regenerate();

            return redirect()->intended(route('menu'));
        }else{
            return redirect(route('login'))->with('error', 'El usuario o contraseña no son validos. ');
        }
    }

    /*public function showRegistrationForm(){
        return view('auth.register');
    }*/

    public function register(Request $request){

        $existingUser = User::where('Correo_Institucional', $request->Correo_Institucional)->first();

        if ($existingUser) {
            // El correo electrónico ya está registrado, muestra un mensaje de error
            return redirect(route('registro'))->with('error', 'El correo electrónico ya está registrado. Por favor, elige otro correo.');
        }

        $user  = new User();

        $user->Nombre_Usuario = $request->Nombre_Usuario;
        $user->Correo_Institucional = $request->Correo_Institucional;
        $user->Contrasena = $request->Contrasena;
        $user->Rol = 'Reportante';

        $user->save();

        Auth::login($user);
        return redirect(route('menu'));
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }

}
