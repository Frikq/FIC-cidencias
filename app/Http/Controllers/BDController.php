<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

session_start();

class BDController extends Controller
{

    public function validarCorreo($correo){
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL))
            return false;
        

        list($usuario, $dominio) = explode('@', $correo);

        if ($dominio !== 'info.uas.edu.mx')
            return false;
        
        return true;
    }

    public function validarContrasena($contrasena){
        if (strlen($contrasena) < 8)
            return false;
        
        if (!preg_match('/[A-Z]/', $contrasena))
            return false;

        if (!preg_match('/[a-z]/', $contrasena))
            return false;

        if (!preg_match('/[0-9]/', $contrasena))
            return false;
    
        return true;
    }

    public function iniciar(Request $request){
        $usuario = DB::table('Usuarios')->where('Nombre_Usuario', $request->Nombre_Usuario)->first();

        if($usuario && Hash::check($request->Contrasena, $usuario->Contrasena)){
            $_SESSION['Nombre_Usuario'] = $request->Nombre_Usuario;
            $_SESSION['Rol'] = $usuario->Rol;
            $_SESSION['Contrasena'] = Hash::make($request->Contrasena);
            $_SESSION['Correo_Institucional'] = $usuario->Correo_Institucional;

            //dd($_SESSION['reportes']);
            return redirect()->intended(route('menu'));
        } else {
            return redirect(route('login'))->with('error', 'El usuario o contraseña no son válidos.');
        }
    }

    public function registrar(Request $request){
        $correo = DB::table('Usuarios')->where('Correo_Institucional', $request->Correo_Institucional)->first();
        $usuario  = DB::table('Usuarios')->where('Nombre_Usuario', $request->Nombre_Usuario)->first();

        if(!BDController::validarCorreo($request->Correo_Institucional))
            return redirect(route('registro'))->with('error', 'El correo ingresado no es valido. Recurda utilizar la extension insitucional.');

        if(!BDController::validarContrasena($request->Contrasena))
            return redirect(route('registro'))->with('error', 'La contraseña no es valida. Usa al menos 8 caracteres, una mayúscula, una minúscula y un número.');

        if ($correo)
            return redirect(route('registro'))->with('error', 'El correo electrónico ya está registrado. Por favor, elige otro correo.');
        

        if ($usuario)
            return redirect(route('registro'))->with('error', 'El nombre de usuario ya está registrado. Por favor, intenta con otro.');
        
        if($request->Contrasena != $request->ConContrasena)
        return redirect(route('registro'))->with('error', 'Las contraseñas no coinciden.');

        
        DB::table('Usuarios')->insert([
            'Correo_Institucional' => $request->Correo_Institucional,
            'Nombre_Usuario' => $request->Nombre_Usuario,
            'Contrasena' => Hash::make($request->Contrasena),
            'Rol' => 'Reportante',
        ]);
        

        $user = DB::table('Usuarios')->where('Nombre_Usuario', $request->Nombre_Usuario)->first();
        if ($user && Hash::check($request->Contrasena, $user->Contrasena)) {
            $_SESSION['Nombre_Usuario'] = $request->Nombre_Usuario;
            $_SESSION['Correo_Institucional'] = $request->Correo_Institucional;
            $_SESSION['Contrasena'] = Hash::make($request->Contrasena);
            $_SESSION['Rol'] = $user->Rol;


            return redirect()->intended(route('menu'));
        }else{
            return redirect(route('registro'))->with('error', 'Ocurrio un error. Por favor, intenta más tarde.');
        }
    }

    public function reporte(){
        $_SESSION['Nombre_Reporte'] = '';
        $_SESSION['Urgencia'] = '';
        $_SESSION['Tipo_Incidencia'] = '';
        $_SESSION['Lugar_Incidencia'] = '';
        $_SESSION['Descripcion_Incidencia'] = '';
        $_SESSION['Estatus'] = '';
        $_SESSION['Reportante'] = '';

        return view('reporte');
        //return redirect(route('menu'));
    }

    public function reportar(Request $request){
        if(isset($_SESSION['Numero_Reporte']) && $_SESSION['Numero_Reporte'] != '' && DB::table('Reportes')->where('Numero_Reporte', $_SESSION['Numero_Reporte'])->first()){
            if($_SESSION['Rol']=="Reportante" && $_SESSION['Estatus'] == 'Pendiente'){
                DB::table('Reportes')->where('Nombre_Usuario', $_SESSION['Nombre_Usuario'])->where('Numero_Reporte', $_SESSION['Numero_Reporte'])
                ->update(['Nombre_Reporte' => $request->Nombre_Reporte,
                'Urgencia' => $request->Urgencia,
                'Tipo_Incidencia' => $request->Tipo_Incidencia,
                'Lugar_Incidencia' => $request->Lugar_Incidencia,
                'Descripcion_Incidencia' => $request->Descripcion_Incidencia,]);
            }else if($_SESSION['Rol']!="Reportante"){
                DB::table('Reportes')->where('Numero_Reporte', $_SESSION['Numero_Reporte'])
                ->update(['Nombre_Reporte' => $request->Nombre_Reporte,
                'Urgencia' => $request->Urgencia,
                'Tipo_Incidencia' => $request->Tipo_Incidencia,
                'Lugar_Incidencia' => $request->Lugar_Incidencia,
                'Descripcion_Incidencia' => $request->Descripcion_Incidencia,
                'Estatus' => $request->Status,]);
            }
            return redirect(route('menu'));
        }


        DB::table('Reportes')->insert([
            'Nombre_Usuario' => $_SESSION['Nombre_Usuario'],
            'Correo_Institucional' => $_SESSION['Correo_Institucional'],
            'Fecha_Elaboracion' => date("Y-m-d H:i:s"),
            'Nombre_Reporte' => $request->Nombre_Reporte,
            'Urgencia' => $request->Urgencia,
            'Tipo_Incidencia' => $request->Tipo_Incidencia,
            'Lugar_Incidencia' => $request->Lugar_Incidencia,
            'Descripcion_Incidencia' => $request->Descripcion_Incidencia,
            'Estatus' => $_SESSION['Rol']=='Reportante'? 'Pendiente':$request->Status,
        ]);
        

        return redirect(route('menu'));
        //return redirect(route('menu'));
    }

    
      
    public function seleccionar($incidencia){
        foreach($_SESSION['reportes'] as $reporte){
            if($reporte->Numero_Reporte == strval($incidencia)){
                $_SESSION['Nombre_Reporte'] = $reporte->Nombre_Reporte;
                $_SESSION['Urgencia'] = $reporte->Urgencia;
                $_SESSION['Tipo_Incidencia'] = $reporte->Tipo_Incidencia;
                $_SESSION['Lugar_Incidencia'] = $reporte->Lugar_Incidencia;
                $_SESSION['Descripcion_Incidencia'] = $reporte->Descripcion_Incidencia;
                $_SESSION['Estatus'] = $reporte->Estatus;
                $_SESSION['Numero_Reporte'] = intval($reporte->Numero_Reporte);
                $_SESSION['Reportante'] = $reporte->Correo_Institucional;
                break;
            }
            
        }
        return view('reporte', compact('incidencia'));
    }

    public function reportes(){
        if (!isset($_SESSION['Nombre_Usuario'])) {
            return redirect(route('login'));
        }

        switch ($_SESSION['Rol']) {
            case 'Administrador':
                $_SESSION['reportes'] = DB::table('Reportes')
                    ->where('Nombre_Usuario', $_SESSION['Nombre_Usuario'])
                    ->orWhere('Fecha_Elaboracion', '>=', now()->subDays(30))
                    ->get();
                break;
            case 'Reportante':
                $_SESSION['reportes'] = DB::table('Reportes')
                    ->where('Nombre_Usuario', $_SESSION['Nombre_Usuario'])
                    ->get();
                break;
            case 'Intendencia':
                $_SESSION['reportes'] = DB::table('Reportes')
                    ->where(function ($query) {
                        $query->orWhere('Nombre_Usuario', $_SESSION['Nombre_Usuario'])
                            ->Where('Fecha_Elaboracion', '>=', now()->subDays(30))
                            ->orWhereIn('Tipo_Incidencia', ['Ventilación', 'Mantenimiento', 'Limpieza', 'Mobiliario']);
                    })
                    ->get();
                break;
            case 'Mantenimiento':
                $_SESSION['reportes'] = DB::table('Reportes')
                    ->where(function ($query) {
                        $query->orWhere('Nombre_Usuario', $_SESSION['Nombre_Usuario'])
                            ->Where('Fecha_Elaboracion', '>=', now()->subDays(30))
                            ->orWhereIn('Tipo_Incidencia', ['Mantenimiento','Mobiliario','Aparatos Electrónicos']);
                    })
                    ->get();
                break;
            case 'Electricista':
                $_SESSION['reportes'] = DB::table('Reportes')
                    ->where(function ($query) {
                        $query->orWhere('Nombre_Usuario', $_SESSION['Nombre_Usuario'])
                            ->Where('Fecha_Elaboracion', '>=', now()->subDays(30))
                            ->orWhereIn('Tipo_Incidencia', ['Aparatos Electrónicos', 'Cableado','Iluminación', 'Suministro Eléctrico', 'Sonido', 'Ventilación']);
                        })
                        ->get();
            break;
            case 'Administrativo':
                $_SESSION['reportes'] = DB::table('Reportes')
                    ->where(function ($query) {
                        $query->orWhere('Nombre_Usuario', $_SESSION['Nombre_Usuario'])
                            ->Where('Fecha_Elaboracion', '>=', now()->subDays(30))
                            ->orWhereIn('Tipo_Incidencia', ['Comportamiento']);
                        })
                        ->get();
            break;
            default:
                break;
        }
        
        
        return view('menu');
    }

    public function eliminar(){
        $incidencia = DB::table('Reportes')->where('Numero_Reporte', $_SESSION['Numero_Reporte'])->first();

        if($incidencia && ($incidencia->Nombre_Usuario == $_SESSION['Nombre_Usuario'] || $_SESSION['Rol']=='Administrador'))
            DB::table('Reportes')->where('Numero_Reporte', $_SESSION['Numero_Reporte'])->delete();


        return redirect(route('menu'));
    }

    public function buscar(Request $request){
        if($_SESSION['Rol'] != 'Administrador')
            return redirect(route('menu'));

        $usuario = DB::table('Usuarios')->where('Correo_Institucional', $request->Correo_Institucional)->first();
        
        if($request->input('accion') == 'buscar'){
            if($usuario){
                $_SESSION['Personal'] = $usuario;
                $mensaje = $usuario->Nombre_Usuario.' es un usuario '.$usuario->Rol.'. Puedes modificar su rol y guardar cambios.';
                if($usuario->Rol == 'Administrador')
                    $mensaje = $_SESSION['Personal']->Nombre_Usuario.' es un administrador por que que no es posible asignarle otro rol.';

                return redirect(route('roles'))->with('error', $mensaje);
            }else

            return redirect(route('roles'))->with('error', 'Este correo electrónico no pertenece a ningún usuario actualmente. Verifica que este en el formato correcto he intenta de nuevo.');
        }else{
            if($usuario){
                if($usuario->Rol == 'Administrador')
                    return redirect(route('roles'))->with('error', 'No es posible asignarle un rol diferente a '.$usuario->Nombre_Usuario.' debido a que es un administrador del sistema ');
    
                DB::table('Usuarios')->where('Nombre_Usuario', $usuario->Nombre_Usuario)->update(['Rol' => $request->Rol]);
                return redirect(route('roles'))->with('error', 'Perfecto!! Ahora '.$usuario->Nombre_Usuario.' es un usuario '.$request->Rol);         
            }
    
            return redirect(route('roles'))->with('error', 'Debes primero seleccionar a un usuario para poder realizar los cambios.');
        }
    }

    public function asignar(Request $request){
        dd($request);
        if(isset($_SESSION['Personal']) && isset($_SESSION['Personal']->Rol)){
            if($_SESSION['Personal']->Rol == 'Administrador')
                return redirect(route('roles'))->with('error', 'No es posible asignarle un rol diferente a '.$_SESSION['Personal']->Nombre_Usuario.' debido a que es un administrador del sistema ');

            DB::table('Usuarios')->where('Nombre_Usuario', $_SESSION['Personal']->Nombre_Usuario)->update(['Rol' => $request->Rol]);
            return redirect(route('roles'))->with('error', 'Perfecto!! Ahora '.$_SESSION['Personal']->Nombre_Usuario.' es un usuario'.$request->Rol);         
        }

        return redirect(route('roles'))->with('error', 'Debes primero seleccionar a un usuario para poder realizar los cambios.');
            
    }

    public function comprobarInicio(){
        if(isset($_SESSION['Nombre_Usuario']))
            return redirect(route('menu'));
        
        return view('index');
    }

    public function comprobarAdmin(){
        if(!isset($_SESSION['Nombre_Usuario']) || (isset($_SESSION['Rol']) && $_SESSION['Rol'] != 'Administrador'))
            return redirect(route('menu'));
        
        return view('roles');
    }

    public function comprobarAcceso(){
        if (!isset($_SESSION['Nombre_Usuario']))
            BDController::cerrarSesion();
    }

    public function cerrarSesion(){
        session_destroy();
        return redirect(route('login'));
    }
}
