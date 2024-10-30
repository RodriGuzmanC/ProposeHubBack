<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Propuesta;
use App\Models\VersionPropuesta;
use Illuminate\Http\Request;

class VersionPropuestaController extends Controller
{
    // Mostrar todas las versiones de propuestas
    public function obtenerTodos($id)
    {
        try {
            $propuesta = Propuesta::find($id);

            if (!$propuesta) {
                //return response()->json(['message' => 'Propuesta no encontrada'], 404);
                throw new \Exception('Propuesta no encontrada', 404);
            }

            // Obtener las versiones de la propuesta específica
            $versiones = VersionPropuesta::where('id_propuesta', $id)->get();

            return response()->json($versiones);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrados' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    public function obtenerUno($id)
    {
        try {
            $version_propuesta = VersionPropuesta::find($id);

            if (!$version_propuesta) {
                //return response()->json(['message' => 'Servicio no encontrado'], 404);
                throw new \Exception('Version no encontrada', 404);
            }

            return response()->json($version_propuesta);
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
                'id_propuesta' => 'nullable|exists:propuestas,id',
                'contenido' => 'nullable|string',
                'en_edicion' => 'required|boolean',
            ]);

            $version_propuesta = VersionPropuesta::create($request->all());
            return response()->json($version_propuesta, 201); // 201 Created
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
            $version_propuesta = VersionPropuesta::find($id);

            if (!$version_propuesta) {
                //return response()->json(['message' => 'Servicio no encontrado'], 404);
                throw new \Exception('Version no encontrada', 404);
            }

            $request->validate([
                'contenido' => 'nullable|string',
            ]);

            $version_propuesta->update($request->all());

            return response()->json($version_propuesta);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al editar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }
}
