<?php

namespace App\Http\Controllers\API;

use App\Models\Propuesta;
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
    }


    // Obtener una propuesta específica
    public function obtenerUno($id)
    {
        $propuesta = Propuesta::with(['estado', 'plantilla', 'servicio', 'usuario'])->find($id);

        if (!$propuesta) {
            return response()->json(['mensaje' => 'Propuesta no encontrada'], 404);
        }

        return response()->json($propuesta);
    }

    public function crear(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'monto' => 'required|numeric',
            'id_estado' => 'required|exists:estado_propuestas,id',
            'id_plantilla' => 'required|exists:plantillas,id',
            'id_servicio' => 'required|exists:servicios,id',
            // MODIFICADO: Elimine id_cliente y agregue id_organizacion
            'id_organizacion' => 'required|exists:organizaciones,id',
            'informacion' => 'required|string',
            'id_usuario' => 'required|exists:usuarios,id',
        ]);

        // Crear el prompt para la IA
        /*$clienteId = $request->input('id_cliente');
        $titulo = $request->input('titulo');
        $monto = $request->input('monto');

        $prompt = "Crear una propuesta para el cliente ID: $clienteId con título '$titulo' y monto '$monto'.";

        // Llamar a la IA en Python para obtener la propuesta
        $url_ia = 'https://7ea0-35-236-250-117.ngrok-free.app/generar-propuesta'; // Cambia esto a tu URL
        $response_ia = Http::post($url_ia, ['comentarios' => $prompt]);

        // Verifica la respuesta de la API
        $data_ia = $response_ia->json();

        if (!isset($data_ia['respuesta'])) {
            return response()->json(['mensaje' => 'No se generó propuesta desde la IA'], 500);
        }

        // Obtener la información de la propuesta generada por la IA
        $informacion_ia = $data_ia['respuesta'];*/

        // Crear la propuesta
        //$propuesta = Propuesta::create(array_merge($request->all(), ['informacion' => $informacion_ia]));
        // MODIFICADO: Desactive $propuesta de arriba y cree el mio justo aca abajo
        $propuesta = Propuesta::create($request->all());
        return response()->json($propuesta, 201);
        // Cargar relaciones y obtener los nombres
        /*$cliente = $propuesta->cliente()->first(['nombre']);
        $plantilla = $propuesta->plantilla()->first();
        $servicio = $propuesta->servicio()->first(['nombre']);
        $estado = $propuesta->estado()->first(['nombre']); // Si también necesitas el nombre del estado

        // Formatear la respuesta
        $respuesta = [
            'titulo' => $propuesta->titulo,
            'cliente' => $cliente ? $cliente->nombre : null,
            'plantilla' => $plantilla ? $plantilla->nombre : null,
            'servicio' => $servicio ? $servicio->nombre : null,
            'estado' => $estado ? $estado->nombre : null,
            'monto' => $propuesta->monto,
            'informacion' => $propuesta->informacion,
            'id_usuario' => $propuesta->id_usuario,
            'created_at' => $propuesta->created_at,
            'updated_at' => $propuesta->updated_at,
        ];

        return response()->json($respuesta, 201);*/
    }

    // Editar una propuesta existente
    public function editar(Request $request, $id)
    {
        $propuesta = Propuesta::find($id);

        if (!$propuesta) {
            return response()->json(['mensaje' => 'Propuesta no encontrada'], 404);
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
    }

    // Eliminar una propuesta
    public function eliminar($id)
    {
        $propuesta = Propuesta::find($id);

        if (!$propuesta) {
            return response()->json(['mensaje' => 'Propuesta no encontrada'], 404);
        }

        $propuesta->delete();
        return response()->json(null, 204); // 204 Sin Contenido
    }
}
