<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Manejar el registro de usuario
    public function register(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'correo' => 'required|string|email|max:255|unique:usuarios',
                'contrasena' => 'required|string',
                'id_rol' => 'required|exists:rol,id',
            ]);

            $usuario = Usuario::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'contrasena_hash' => Hash::make($request->contrasena),
                'id_rol' => $request->id_rol,
            ]);

            return response()->json(['mensaje' => 'Usuario registrado exitosamente.', 'usuario' => $usuario], 201);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No regitrado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Obtener todos los usuarios
    public function index()
    {
        try {
            $usuarios = Usuario::with('rol')->get(); // Suponiendo que hay una relación con Rol
            return response()->json($usuarios);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Error interno en usuario' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Obtener un usuario específico
    public function show($id)
    {
        try {
            $usuario = Usuario::with('rol')->find($id);

            if (!$usuario) {
                //return response()->json(['mensaje' => 'Usuario no encontrado.'], 404);
                throw new \Exception('Usuario no encontrado', 404);
            }

            return response()->json($usuario);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Actualizar un usuario
    public function update(Request $request, $id)
    {
        try {
            $usuario = Usuario::find($id);

            if (!$usuario) {
                //return response()->json(['mensaje' => 'Usuario no encontrado.'], 404);
                throw new \Exception('Usuario no encontrado', 404);
            }

            $request->validate([
                'nombre' => 'nullable|string|max:255',
                'correo' => 'nullable|string|email|max:255|unique:usuarios,correo,' . $usuario->id,
                'id_rol' => 'nullable|exists:rol,id',
                'contrasena' => 'nullable|string|min:8|confirmed',
            ]);

            $usuario->nombre = $request->nombre ?? $usuario->nombre;
            $usuario->correo = $request->correo ?? $usuario->correo;
            $usuario->id_rol = $request->id_rol ?? $usuario->id_rol;

            if ($request->filled('contrasena')) {
                $usuario->contrasena_hash = Hash::make($request->contrasena);
            }

            $usuario->save();

            return response()->json(['mensaje' => 'Usuario actualizado exitosamente.', 'usuario' => $usuario]);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al editar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        try {
            $usuario = Usuario::find($id);

            if (!$usuario) {
                //return response()->json(['mensaje' => 'Usuario no encontrado.'], 404);
                throw new \Exception('Usuario no encontrado', 404);
            }

            $usuario->delete();
            return response()->json(['mensaje' => 'Usuario eliminado exitosamente.']);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al eliminar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Manejar el inicio de sesión de usuario
    public function login(Request $request)
    {
        try {
            $request->validate([
                'correo' => 'required|string|email',
                'contrasena' => 'required|string',
            ]);

            $usuario = Usuario::where('correo', $request->correo)->first();

            if ($usuario && Hash::check($request->contrasena, $usuario->contrasena_hash)) {
                session(['usuario_id' => $usuario->id, 'rol_id' => $usuario->id_rol]);
                $usuarioData = $usuario->makeHidden(['contrasena_hash', 'created_at', 'updated_at']);
                return response()->json($usuarioData);
            }

            //return response()->json(['mensaje' => 'Las credenciales son incorrectas.'], 401);
            throw new \Exception('Las credenciales son incorrectas', 404);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al loguearse' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Manejar el cierre de sesión
    public function logout(Request $request)
    {
        $request->session()->forget(['usuario_id', 'rol_id']);
        return response()->json(['mensaje' => 'Cierre de sesión exitoso.']);
    }
}
