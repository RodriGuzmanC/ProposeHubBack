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
        $servicios = Servicio::all();
        return response()->json($servicios);
    }

    // Método para mostrar un servicio específico
    public function obtenerUno($id)
    {
        $servicio = Servicio::find($id);
        
        if (!$servicio) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }

        return response()->json($servicio);
    }

    // Método para almacenar un nuevo servicio
    public function crear(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $servicio = Servicio::create($request->all());
        return response()->json($servicio, 201); // 201 Created
    }

    // Método para actualizar un servicio existente
    public function editar(Request $request, $id)
    {
        $servicio = Servicio::find($id);
        
        if (!$servicio) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $servicio->update($request->all());
        return response()->json($servicio);
    }

    // Método para eliminar un servicio
    public function eliminar($id)
    {
        $servicio = Servicio::find($id);
        
        if (!$servicio) {
            return response()->json(['message' => 'Servicio no encontrado'], 404);
        }

        $servicio->delete();
        return response()->json(null, 204); // 204 No Content
    }
}
