<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Organizacion;
use Illuminate\Http\Request;

class OrganizacionController extends Controller
{
    // Método para listar todas las organizaciones
    public function obtenerTodos()
    {
        try {
            $organizaciones = Organizacion::all();
            return response()->json($organizaciones);
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
            $organizacion = Organizacion::find($id);

            if (!$organizacion) {
                //return response()->json(['message' => 'Organización no encontrada'], 404);
                throw new \Exception('Organizacion no encontrada', 404);
            }

            return response()->json($organizacion);
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
                'telefono' => 'nullable|string|max:20',
                'correo' => 'nullable|string|email|max:255',
            ]);

            $organizacion = Organizacion::create($request->all());
            return response()->json($organizacion, 201); // 201 Created
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
            $organizacion = Organizacion::find($id);

            if (!$organizacion) {
                //return response()->json(['message' => 'Organización no encontrada'], 404);
                throw new \Exception('Organizacion no encontrada', 404);
            }

            $request->validate([
                'nombre' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'correo' => 'nullable|string|email|max:255',
            ]);

            $organizacion->update($request->all());
            return response()->json($organizacion);
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
            $organizacion = Organizacion::find($id);

            if (!$organizacion) {
                //return response()->json(['message' => 'Organización no encontrada'], 404);
                throw new \Exception('Organizacion no encontrada', 404);
            }

            $organizacion->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al eliminar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }
}
