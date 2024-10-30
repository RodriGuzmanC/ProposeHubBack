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
    // Obtener todas las propuestas
    /*public function obtenerTodos()
    {
        $propuestas = Propuesta::with(['estado', 'plantilla', 'servicio', 'usuario'])->get();
        return response()->json($propuestas);
    }*/
    public function obtenerTodos()
    {
        try {
            $propuestas = Propuesta::with(['estado:id,nombre', 'plantilla:id,nombre', 'usuario:id,nombre'])
                ->select('id', 'id_cliente', 'id_organizacion', 'titulo', 'monto', 'id_estado', 'id_plantilla', 'id_servicio', 'informacion', 'fecha_creacion', 'id_usuario', 'version_publicada') // Excluye 'contenido'
                ->get();

            // Transformar el resultado para reemplazar las relaciones por sus nombres
            $resultados = $propuestas->map(function ($propuesta) {
                return [
                    'id' => $propuesta->id,
                    'id_organizacion' => $propuesta->id_organizacion,
                    'organizacion_nombre' => $propuesta->organizacion->nombre ?? null,
                    'titulo' => $propuesta->titulo,
                    'monto' => $propuesta->monto,
                    'id_estado' => $propuesta->id_estado,
                    'estado_nombre' => $propuesta->estado->nombre ?? null,
                    'id_plantilla' => $propuesta->id_plantilla,
                    'plantilla_nombre' => $propuesta->plantilla->nombre ?? null,
                    'id_servicio' => $propuesta->id_servicio,
                    'servicio_nombre' => $propuesta->servicio->nombre ?? null,
                    'informacion' => $propuesta->informacion,
                    'fecha_creacion' => $propuesta->fecha_creacion,
                    'id_usuario' => $propuesta->id_usuario,
                    'usuario_nombre' => $propuesta->usuario->nombre ?? null,
                    'version_publicada' => $propuesta->version_publicada,
                    // 'contenido' se omite
                ];
            });

            return response()->json($resultados);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrados' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }


    // Obtener una propuesta específica
    public function obtenerUno($id)
    {
        try {
            $propuesta = Propuesta::with(['estado', 'plantilla', 'servicio', 'usuario'])->find($id);

            if (!$propuesta) {
                //return response()->json(['mensaje' => 'Propuesta no encontrada'], 404);
                throw new \Exception('Propuesta no encontrada', 404);
            }

            return response()->json($propuesta);
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
                'titulo' => 'required|string|max:255',
                'monto' => 'required|numeric',
                'id_estado' => 'required|exists:estado_propuestas,id',
                'id_plantilla' => 'required|exists:plantillas,id',
                'id_servicio' => 'required|exists:servicios,id',
                'id_organizacion' => 'required|exists:organizaciones,id',
                'informacion' => 'sometimes|string',
                'id_usuario' => 'required|exists:usuarios,id',
            ]);

            // MODIFICADO: Desactive $propuesta de arriba y cree el mio justo aca abajo
            $propuesta = Propuesta::create($request->all());
            return response()->json($propuesta, 201);

        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No creado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Editar una propuesta existente
    public function editar(Request $request, $id)
    {
        try {
            $propuesta = Propuesta::find($id);

            if (!$propuesta) {
                //return response()->json(['mensaje' => 'Propuesta no encontrada'], 404);
                throw new \Exception('Propuesta no encontrada', 404);
            }

            $request->validate([
                'id_organizacion' => 'sometimes|exists:organizaciones,id',
                'titulo' => 'sometimes|string|max:255',
                'monto' => 'sometimes|numeric',
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
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al editar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Eliminar una propuesta
    public function eliminar($id)
    {
        try {
            $propuesta = Propuesta::find($id);

            if (!$propuesta) {
                //return response()->json(['mensaje' => 'Propuesta no encontrada'], 404);
                throw new \Exception('Propuesta no encontrada', 404);
            }

            $propuesta->delete();
            return response()->json($propuesta, 200); // 204 Sin Contenido
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al eliminar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }


    public function respuestaAI(Request $request)
    {
        try {
            // Validar que los parámetros necesarios estén presentes
            $request->validate([
                'id_servicio' => 'required|exists:servicios,id', // Asegúrate que la tabla y columna son correctas
                'id_organizacion' => 'required|exists:organizaciones,id', // Asegúrate que la tabla y columna son correctas
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
            $nombreServicio = Servicio::find($idServicio)->nombre; // Ajusta el campo según tu modelo
            $nombreOrganizacion = Organizacion::find($idOrganizacion)->nombre; // Ajusta el campo según tu modelo

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

            //return response()->json($dataParaIA, 201);

            // Llamar a la IA en Python para obtener la propuesta
            $url_ia = 'https://a38b-34-32-222-88.ngrok-free.app/generar-propuesta'; // Cambia esto a tu URL
            $response_ia = Http::post($url_ia, $dataParaIA);

            // Verifica la respuesta de la API
            //$data_ia = $response_ia->json();
            if ($response_ia->successful()) {
                // Decodificar la respuesta y devolverla en formato JSON
                return response()->json($response_ia->json(), 201);
            } else {
                // En caso de error, devolver un mensaje de error
                /*return response()->json([
                'error' => 'Hubo un problema al obtener la propuesta de la API de IA',
                'detalles' => $response_ia->body()
            ], $response_ia->status());*/
                throw new \Exception('Hubo un problema al obtener la propuesta de la API de IA', 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al obtener respuesta' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
        /*if (!isset($data_ia['respuesta'])) {
            return response()->json(['mensaje' => 'No se generó propuesta desde la IA'], 500);
        }

        // Obtener la información de la propuesta generada por la IA
        $informacion_ia = $data_ia['respuesta'];

        return response()->json($data_ia, 201);*/
    }
}
