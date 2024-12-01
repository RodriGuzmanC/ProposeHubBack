<?php

namespace App\Http\Controllers\API;

use App\Models\Propuesta;
use App\Models\Servicio;
use App\Models\Organizacion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PropuestaController extends Controller
{
    
    public function obtenerTodos(Request $request)
    {
        try {

            $estado = $request->input('estado', 1);
            $propuestas = Propuesta::with(['estado', 'plantilla', 'usuario'])->get();

            $propuestas->each(function ($propuesta) {
                $propuesta->plantilla->makeHidden('contenido');
                $propuesta->makeHidden('html');
                $propuesta->makeHidden('css');

            });

            return response()->json($propuestas);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => 'Error del servidor'
            ], 500);
        }
    }


    // Obtener una propuesta específica
    public function obtenerUno($id)
    {
        try {
            $propuesta = Propuesta::with(['organizacion', 'estado', 'plantilla', 'servicio', 'usuario'])->find($id);

            if (!$propuesta) {
                return response()->json([
                    'mensaje' => 'Propuesta no encontrada',
                    'error' => 'No encontrado'
                ], 404);
            }

            return response()->json($propuesta);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => 'Error del servidor'
            ], 500);
        }
    }

    public function crear(Request $request)
    {
        try {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'monto' => 'required|numeric',
                'id_estado' => 'required|exists:estado_propuestas,id',
                'id_plantilla' => 'required|exists:plantillas,id',
                'id_servicio' => 'required|exists:servicios,id',
                'id_organizacion' => 'required|exists:organizaciones,id',
                'informacion' => 'sometimes|string',
                'id_usuario' => 'required|exists:usuarios,id',
            ]);

            $propuesta = Propuesta::create($request->all());
            return response()->json($propuesta, 201);

        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => 'Error del servidor'
            ], 500);
        }
    }

    // Editar una propuesta existente
    public function editar(Request $request, $id)
    {
        try {
            $propuesta = Propuesta::find($id);

            if (!$propuesta) {
                return response()->json([
                    'mensaje' => 'Propuesta no encontrada',
                    'error' => 'No encontrada'
                ], 404);
            }

            $request->validate([
                'id_organizacion' => 'sometimes|exists:organizaciones,id',
                'titulo' => 'sometimes|string|max:255',
                'monto' => 'sometimes|numeric',
                'id_cliente' => 'sometimes|numeric',
                'id_estado' => 'sometimes|exists:estado_propuestas,id',
                'id_plantilla' => 'sometimes|exists:plantillas,id',
                'id_servicio' => 'sometimes|exists:servicios,id',
                'informacion' => 'sometimes|string',
                'version_publicada' => 'sometimes|exists:versiones_propuestas,id',
                'html' => 'sometimes|string',
                'css' => 'sometimes|string',
            ]);

            // Solo actualiza los campos que fueron proporcionados en el request
            $propuesta->update($request->only([
                'id_organizacion',
                'titulo',
                'monto',
                'id_cliente',
                'id_estado',
                'id_plantilla',
                'id_servicio',
                'informacion',
                'version_publicada',
                'html',
                'css'
            ]));

            return response()->json($propuesta);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => 'Error del servidor'
            ], 500);
        }
    }

    // Eliminar una propuesta
    public function eliminar($id)
    {
        try {
            $propuesta = Propuesta::find($id);

            if (!$propuesta) {
                return response()->json([
                    'mensaje' => 'Propuesta no encontrada',
                    'error' => 'No encontrada'
                ], 404);
            }

            $propuesta->delete();
            return response()->json($propuesta, 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => 'Error del servidor'
            ], 500);
        }
    }


    public function respuestaAI(Request $request)
    {
        try {
            // Validar que los parámetros necesarios estén presentes
            $request->validate([
                'id_servicio' => 'required|exists:servicios,id', 
                'id_organizacion' => 'required|exists:organizaciones,id',
                'titulo' => 'required|string|max:255',
                'monto' => 'required|numeric',
                'descripcionEmpresa' => 'required|string',
                'indicaciones' => 'required|string',
                'estructura' => 'required|array'
            ]);

            // Obtener el ID del servicio y la organización
            $idServicio = $request->input('id_servicio');
            $idOrganizacion = $request->input('id_organizacion');

            // Consultar el nombre del servicio y de la organización en la base de datos
            $nombreServicio = Servicio::find($idServicio)->nombre; 
            $nombreOrganizacion = Organizacion::find($idOrganizacion)->nombre;

            // Crear el array para enviar a la IA
            $dataParaIA = [
                'nombre_servicio' => $nombreServicio,
                'nombre_organizacion' => $nombreOrganizacion,
                'titulo' => $request->input('titulo'),
                'monto' => $request->input('monto'),
                'descripcion_empresa' => $request->input('descripcionEmpresa'),
                'indicaciones' => $request->input('indicaciones'),
                'estructura' => $request->input('estructura')
            ];

            // Llamar a la IA en Python para obtener la propuesta
            $url_ia = env('API_AI');
            $response_ia = Http::post($url_ia, $dataParaIA);

            // Verifica la respuesta de la API
            if ($response_ia->successful()) {
                // Decodificar la respuesta y devolverla en formato JSON
                return response()->json($response_ia->json(), 201);
            } else {
                return response()->json([
                    'mensaje' => 'Hubo un problema al obtener la propuesta de la API de IA',
                    'error' => 'No encontrada'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => 'Error del servidor'
            ], 500);
        }
        
    }
}
