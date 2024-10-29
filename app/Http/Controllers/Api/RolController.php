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
        $roles = Rol::all();
        return response()->json($roles);
    }

    // Método para mostrar una organización específica
    public function obtenerUno($id)
    {
        $rol = Rol::find($id);
        
        if (!$rol) {
            return response()->json(['message' => 'Organización no encontrada'], 404);
        }

        return response()->json($rol);
    }

    // Método para almacenar una nueva organización
    public function crear(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $rol = Rol::create($request->all());
        return response()->json($rol, 201); // 201 Created
    }

    // Método para actualizar una organización existente
    public function editar(Request $request, $id)
    {
        $rol = Rol::find($id);
        
        if (!$rol) {
            return response()->json(['message' => 'Organización no encontrada'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|string|email|max:255',
        ]);

        $rol->update($request->all());
        return response()->json($rol);
    }

    // Método para eliminar una organización
    public function eliminar($id)
    {
        $rol = Rol::find($id);
        
        if (!$rol) {
            return response()->json(['message' => 'Organización no encontrada'], 404);
        }

        $rol->delete();
        return response()->json(null, 204); // 204 No Content
    }
    
}
