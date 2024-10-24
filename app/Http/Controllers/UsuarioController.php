<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;  // Importar el modelo Rol
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    // Mostrar el formulario de registro
    public function showRegisterForm()
    {
        $roles = Rol::all();  // Obtener todos los roles
        return view('auth.register', compact('roles'));  // Pasar roles a la vista
    }

    // Manejar el registro de usuario
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuarios',
            'contrasena' => 'required|string|confirmed|min:8',
            'id_rol' => 'required|exists:rol,id',  // Validar el rol
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contrasena_hash' => Hash::make($request->contrasena),
            'id_rol' => $request->id_rol,  // Asignar el rol desde la solicitud
        ]);

        return redirect()->route('login');  // Redirige a la página de inicio de sesión
    }

    // Mostrar el formulario de inicio de sesión
    public function showLoginForm()
    {
        return view('auth.login');  // Vista para el inicio de sesión
    }

    // Manejar el inicio de sesión de usuario
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|string|email',
            'contrasena' => 'required|string',
        ]);

        $usuario = Usuario::where('correo', $request->correo)->first();

        if ($usuario && Hash::check($request->contrasena, $usuario->contrasena_hash)) {
            // Guardar la información del usuario en la sesión
            session(['usuario_id' => $usuario->id, 'rol_id' => $usuario->id_rol]);
            return redirect()->route('dashboard');  // Redirige al dashboard
        }
        

        return back()->withErrors([
            'correo' => 'Las credenciales son incorrectas.',
        ]);
    }

    // Manejar el cierre de sesión
    public function logout(Request $request)
    {
        $request->session()->forget(['usuario_id', 'rol_id']);  // Eliminar el usuario y rol de la sesión
        return redirect()->route('login');  // Redirige a la página de inicio de sesión
    }

    // Mostrar el dashboard
    public function dashboard()
    {
        return view('dashboard.index'); // Asegúrate de que la vista exista
    }
}
