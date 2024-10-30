<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    // Método para listar todos los servicios
    public function obtenerTodos()
    {
        try {
            $servicios = Servicio::all();
            return response()->json($servicios);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrados' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Método para mostrar un servicio específico
    public function obtenerUno($id)
    {
        try {
            $servicio = Servicio::find($id);

            if (!$servicio) {
                //return response()->json(['message' => 'Servicio no encontrado'], 404);
                throw new \Exception('Servicio no encontrado', 404);
            }

            return response()->json($servicio);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Método para almacenar un nuevo servicio
    public function crear(Request $request)
    {
        try {


            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
            ]);
            $servicio = Servicio::create($request->all());
            return response()->json($servicio, 201); // 201 Created
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No creado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Método para actualizar un servicio existente
    public function editar(Request $request, $id)
    {
        try {
            $servicio = Servicio::find($id);

            if (!$servicio) {
                //return response()->json(['message' => 'Servicio no encontrado'], 404);
                throw new \Exception('Servicio no encontrado', 404);
            }

            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
            ]);

            $servicio->update($request->all());
            return response()->json($servicio);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al editar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Método para eliminar un servicio
    public function eliminar($id)
    {
        try {
            $servicio = Servicio::find($id);

            if (!$servicio) {
                //return response()->json(['message' => 'Servicio no encontrado'], 404);
                throw new \Exception('Servicio no encontrado', 404);
            }

            $servicio->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al eliminar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }
}
