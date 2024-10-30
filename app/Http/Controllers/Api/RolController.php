<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    // Método para listar todas las organizaciones
    public function obtenerTodos()
    {
        try {
            $roles = Rol::all();
            return response()->json($roles);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrados' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Método para mostrar una organización específica
    public function obtenerUno($id)
    {
        try {
            $rol = Rol::find($id);

            if (!$rol) {
                //return response()->json(['message' => 'Organización no encontrada'], 404);
                throw new \Exception('Rol no encontrado', 404);
            }

            return response()->json($rol);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Método para almacenar una nueva organización
    public function crear(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:255',
            ]);

            $rol = Rol::create($request->all());
            return response()->json($rol, 201); // 201 Created
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No creado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Método para actualizar una organización existente
    public function editar(Request $request, $id)
    {
        try {
            $rol = Rol::find($id);

            if (!$rol) {
                //return response()->json(['message' => 'Organización no encontrada'], 404);
                throw new \Exception('Rol no encontrado', 404);
            }

            $request->validate([
                'nombre' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'correo' => 'nullable|string|email|max:255',
            ]);

            $rol->update($request->all());
            return response()->json($rol);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al editar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Método para eliminar una organización
    public function eliminar($id)
    {
        try {
            $rol = Rol::find($id);

            if (!$rol) {
                //return response()->json(['message' => 'Organización no encontrada'], 404);
                throw new \Exception('Rol no encontrado', 404);
            }

            $rol->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al eliminar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }
}
