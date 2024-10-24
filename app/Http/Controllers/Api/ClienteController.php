<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function obtenerTodos()
    {
        $clientes = Cliente::with('organizacion')->get();

        // Formatear la respuesta
        $clientesFormateados = $clientes->map(function ($cliente) {
            return [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'correo' => $cliente->correo,
                'telefono' => $cliente->telefono,
                'organizacion' => $cliente->organizacion ? $cliente->organizacion->nombre : null, // Nombre de la organización
                'created_at' => $cliente->created_at,
                'updated_at' => $cliente->updated_at,
            ];
        });

        return response()->json($clientesFormateados);
    }


    public function obtenerUno($id)
    {
        $cliente = Cliente::with('organizacion')->find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        // Formatear la respuesta
        $clienteFormateado = [
            'id' => $cliente->id,
            'nombre' => $cliente->nombre,
            'correo' => $cliente->correo,
            'telefono' => $cliente->telefono,
            'organizacion_id' => $cliente->organizacion ? $cliente->organizacion->id : null, // Nombre de la organización
            'organizacion' => $cliente->organizacion ? $cliente->organizacion->nombre : null, // Nombre de la organización
            'created_at' => $cliente->created_at,
            'updated_at' => $cliente->updated_at,
        ];

        return response()->json($clienteFormateado);
    }

    public function crear(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'id_organizacion' => 'nullable|exists:organizaciones,id',
        ]);

        // Crear el cliente
        $cliente = Cliente::create($request->all());

        // Cargar el nombre de la organización si existe
        if ($cliente->id_organizacion) {
            $cliente->organizacion = $cliente->organizacion()->first(['nombre']);
        }

        return response()->json($cliente, 201); // 201 Created
    }


    public function editar(Request $request, $id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'id_organizacion' => 'nullable|exists:organizaciones,id',
        ]);

        $cliente->update($request->all());
        return response()->json($cliente);
    }

    public function eliminar($id)
    {
        $cliente = Cliente::find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado'], 404);
        }

        $cliente->delete();
        return response()->json(null, 204); // 204 No Content
    }
}
