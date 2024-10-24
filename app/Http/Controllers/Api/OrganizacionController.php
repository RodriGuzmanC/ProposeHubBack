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
        $organizaciones = Organizacion::all();
        return response()->json($organizaciones);
    }

    // Método para mostrar una organización específica
    public function obtenerUno($id)
    {
        $organizacion = Organizacion::find($id);
        
        if (!$organizacion) {
            return response()->json(['message' => 'Organización no encontrada'], 404);
        }

        return response()->json($organizacion);
    }

    // Método para almacenar una nueva organización
    public function crear(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|string|email|max:255',
        ]);

        $organizacion = Organizacion::create($request->all());
        return response()->json($organizacion, 201); // 201 Created
    }

    // Método para actualizar una organización existente
    public function editar(Request $request, $id)
    {
        $organizacion = Organizacion::find($id);
        
        if (!$organizacion) {
            return response()->json(['message' => 'Organización no encontrada'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|string|email|max:255',
        ]);

        $organizacion->update($request->all());
        return response()->json($organizacion);
    }

    // Método para eliminar una organización
    public function eliminar($id)
    {
        $organizacion = Organizacion::find($id);
        
        if (!$organizacion) {
            return response()->json(['message' => 'Organización no encontrada'], 404);
        }

        $organizacion->delete();
        return response()->json(null, 204); // 204 No Content
    }
    
}
