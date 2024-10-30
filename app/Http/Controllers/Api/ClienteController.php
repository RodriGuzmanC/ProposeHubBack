<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClienteController extends Controller
{
    public function obtenerTodos()
    {
        try {
            $clientes = Cliente::with('organizacion')->get();

            // Formatear la respuesta
            $clientesFormateados = $clientes->map(function ($cliente) {
                return [
                    'id' => $cliente->id,
                    'nombre' => $cliente->nombre,
                    'correo' => $cliente->correo,
                    'telefono' => $cliente->telefono,
                    'id_organizacion' => $cliente->id_organizacion,
                    'contrasena_hash' => $cliente->contrasena_hash,
                    'organizacion' => $cliente->organizacion ? $cliente->organizacion->nombre : null, // Nombre de la organización
                    'created_at' => $cliente->created_at,
                    'updated_at' => $cliente->updated_at,
                ];
            });

            return response()->json($clientesFormateados);
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
            $cliente = Cliente::with('organizacion')->find($id);

            if (!$cliente) {
                //return response()->json(['message' => 'Cliente no encontrado'], 404);
                throw new \Exception('Cliente no encontrado', 404);
            }

            // Formatear la respuesta
            $clienteFormateado = [
                'id' => $cliente->id,
                'nombre' => $cliente->nombre,
                'correo' => $cliente->correo,
                'telefono' => $cliente->telefono,
                'contrasena_hash' => $cliente->contrasena_hash,
                'organizacion_id' => $cliente->organizacion ? $cliente->organizacion->id : null, // Nombre de la organización
                'organizacion' => $cliente->organizacion ? $cliente->organizacion->nombre : null, // Nombre de la organización
                'created_at' => $cliente->created_at,
                'updated_at' => $cliente->updated_at,
            ];

            return response()->json($clienteFormateado);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    public function crear(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'correo' => 'nullable|email',
                'telefono' => 'nullable|string|max:20',
                'id_organizacion' => 'nullable|exists:organizaciones,id',
            ]);

            // Crear el cliente
            //$cliente = Cliente::create($request->all());
            // Generar una contraseña aleatoria
            $contrasena = Str::random(10); // Cambia el número si deseas una contraseña más larga

            // Crear el cliente
            $cliente = Cliente::create(array_merge($request->all(), [
                'contrasena_hash' => $contrasena,
            ]));

            // Cargar el nombre de la organización si existe
            if ($cliente->id_organizacion) {
                $cliente->organizacion = $cliente->organizacion()->first(['nombre']);
            }

            return response()->json($cliente, 201); // 201 Created
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No creado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'correo' => 'required|string|email',
                'contrasena' => 'required|string',
            ]);

            $cliente = Cliente::where('correo', $request->correo)->first();

            if ($cliente && $request->contrasena == $cliente->contrasena_hash) {
                //session(['usuario_id' => $cliente->id, 'rol_id' => $cliente->id_rol]);
                $clienteData = $cliente->makeHidden(['contrasena_hash', 'created_at', 'updated_at']);
                return response()->json($clienteData);
            }

            //return response()->json(['mensaje' => 'Las credenciales son incorrectas.'], 401);
            throw new \Exception('Las credenciales son incorrectas.', 404);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Error al loguearse' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }


    public function editar(Request $request, $id)
    {
        try {
            $cliente = Cliente::find($id);

            if (!$cliente) {
                //return response()->json(['message' => 'Cliente no encontrado'], 404);
                throw new \Exception('Cliente no encontrado', 404);
            }

            $request->validate([
                'nombre' => 'required|string|max:255',
                'correo' => 'nullable|email',
                'telefono' => 'nullable|string|max:20',
                'id_organizacion' => 'nullable|exists:organizaciones,id',
            ]);

            $cliente->update($request->all());
            return response()->json($cliente);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al editar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    public function eliminar($id)
    {
        try {
            $cliente = Cliente::find($id);

            if (!$cliente) {
                //return response()->json(['message' => 'Cliente no encontrado'], 404);
                throw new \Exception('Cliente no encontrado', 404);
            }

            $cliente->delete();
            return response()->json($cliente, 200); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al eliminar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }
}
