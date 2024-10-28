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
        $propuesta = Propuesta::find($id);

        if (!$propuesta) {
            return response()->json(['message' => 'Propuesta no encontrada'], 404);
        }

        // Obtener las versiones de la propuesta específica
        $versiones = VersionPropuesta::where('id_propuesta', $id)->get();

        return response()->json($versiones);
    }

    // Método para mostrar un servicio específico
    public function obtenerUno($id)
    {
        $version_propuesta = VersionPropuesta::find($id);

        if (!$version_propuesta) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }

        return response()->json($version_propuesta);
    }

    // Método para almacenar un nuevo servicio
    public function crear(Request $request)
    {
        $request->validate([
            'id_propuesta' => 'nullable|exists:propuestas,id',
            'contenido' => 'nullable|string',
            'en_edicion' => 'required|boolean',
        ]);

        $version_propuesta = VersionPropuesta::create($request->all());
        return response()->json($version_propuesta, 201); // 201 Created
    }

    // Método para actualizar un servicio existente
    public function editar(Request $request, $id)
    {
        $version_propuesta = VersionPropuesta::find($id);

        if (!$version_propuesta) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }

        $request->validate([
            'contenido' => 'nullable|string',
        ]);

        $version_propuesta->update($request->all());
        
        return response()->json($version_propuesta);
    }
}
