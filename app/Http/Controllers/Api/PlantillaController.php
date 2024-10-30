<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use Illuminate\Http\Request;

class PlantillaController extends Controller
{
    public function obtenerTodos()
    {
        try {
            $plantillas = Plantilla::all();

            // Formatear la respuesta
            $plantillasFormateadas = $plantillas->map(function ($plantilla) {
                return [
                    'id' => $plantilla->id,
                    'nombre' => $plantilla->nombre,
                    'contenido' => $plantilla->contenido,
                    'created_at' => $plantilla->created_at,
                    'updated_at' => $plantilla->updated_at,
                ];
            });

            return response()->json($plantillasFormateadas);
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
            $plantilla = Plantilla::find($id);

            if (!$plantilla) {
                //return response()->json(['message' => 'Plantilla no encontrada'], 404);
                throw new \Exception('Plantilla no encontrada', 404);
            }

            // Formatear la respuesta
            $plantillaFormateada = [
                'id' => $plantilla->id,
                'nombre' => $plantilla->nombre,
                'contenido' => $plantilla->contenido,
                'created_at' => $plantilla->created_at,
                'updated_at' => $plantilla->updated_at,
            ];

            return response()->json($plantillaFormateada);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }




    public function obtenerContenido($id)
    {
        try {
            $plantilla = Plantilla::find($id);

            if (!$plantilla) {
                //return response()->json(['message' => 'Plantilla no encontrada'], 404);
                throw new \Exception('Plantilla no encontrada', 404);
            }

            // Decodificar el contenido JSON almacenado
            $contenido = json_decode($plantilla->contenido, true);

            // Verificar si la decodificación fue exitosa
            if (empty($plantilla->contenido)) {
                $contenido = [];
            } else if (json_last_error() !== JSON_ERROR_NONE) {
                //return response()->json(['message' => 'Contenido no válido'], 400);
                throw new \Exception('Contenido no valido', 404);
            }

            // Devolver el contenido como se espera en GrapesJS
            return response()->json(['data' => $contenido]);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No obtenido' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }



    public function editarContenido(Request $request, $id)
    {
        try {
            $plantilla = Plantilla::find($id);

            if (!$plantilla) {
                //return response()->json(['message' => 'Plantilla no encontrada'], 404);
                throw new \Exception('Plantilla no encontrada', 404);
            }

            // Validar los datos de entrada. Asegúrate de que el contenido se esté enviando como JSON.
            $request->validate([
                'data' => 'required', // Debe ser un array
            ]);

            // Convertir el contenido a JSON y almacenarlo
            $plantilla->contenido = $request->input('data'); // Asegúrate de que sea un JSON válido
            $plantilla->save();

            return response()->json([
                'message' => 'Contenido actualizado correctamente',
                'plantilla' => $plantilla,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al editar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }







    public function crear(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
            ]);


            // Crear la plantilla
            $plantilla = Plantilla::create([
                'nombre' => $request->input('nombre'),
                'descripcion' => $request->input('descripcion'),
            ]);

            return response()->json($plantilla, 201); // 201 Created
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No creado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    public function editar(Request $request, $id)
    {
        try {
            $plantilla = Plantilla::find($id);

            if (!$plantilla) {
                //return response()->json(['message' => 'Plantilla no encontrada'], 404);
                throw new \Exception('Plantilla no encontrada', 404);
            }

            // Validar los datos de entrada
            $request->validate([
                'nombre' => 'sometimes|required|string|max:255',
                'contenido' => 'sometimes|nullable|string',
            ]);

            // Actualizar la plantilla con los datos del request
            $plantilla->update($request->all());

            return response()->json($plantilla);
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
            $plantilla = Plantilla::find($id);

            if (!$plantilla) {
                //return response()->json(['message' => 'Plantilla no encontrada'], 404);
                throw new \Exception('Plantilla no encontrada', 404);
            }

            $plantilla->delete();
            return response()->json($plantilla, 200); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al eliminar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }
}
