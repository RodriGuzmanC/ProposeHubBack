<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Propuesta;
use App\Models\VersionPropuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class VersionPropuestaController extends Controller
{
    // Mostrar todas las versiones de propuestas
    public function obtenerTodos($id)
    {
        try {
            $propuesta = Propuesta::find($id);

            if (!$propuesta) {
                //return response()->json(['message' => 'Propuesta no encontrada'], 404);
                throw new \Exception('Propuesta no encontrada', 404);
            }

            // Obtener las versiones de la propuesta específica
            $versiones = VersionPropuesta::where('id_propuesta', $id)->get();

            $versionesFormateadas = $versiones->map(function ($version) {
                return [
                    'id' => $version->id,
                    'id_propuesta' => $version->id_propuesta,
                    'version_numero' => $version->version_numero,
                    'fecha_creacion' => $version->fecha_creacion,
                    'en_edicion' => $version->en_edicion,
                ];
            });

            return response()->json($versionesFormateadas);
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
            $version_propuesta = VersionPropuesta::find($id);

            if (!$version_propuesta) {
                //return response()->json(['message' => 'Servicio no encontrado'], 404);
                throw new \Exception('Version no encontrada', 404);
            }

            return response()->json($version_propuesta);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Método para almacenar un nuevo servicio
    public function crear(Request $request)
    {
        try {
            $request->validate([
                'id_propuesta' => 'nullable|exists:propuestas,id',
                'contenido' => 'nullable|string',
                'en_edicion' => 'required|boolean',
            ]);

            $version_propuesta = VersionPropuesta::create($request->all());
            return response()->json($version_propuesta, 201); // 201 Created
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No creado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    // Método para actualizar un servicio existente
    public function editar(Request $request, $id)
    {
        try {
            $version_propuesta = VersionPropuesta::find($id);

            if (!$version_propuesta) {
                //return response()->json(['message' => 'Servicio no encontrado'], 404);
                throw new \Exception('Version no encontrada', 404);
            }

            $request->validate([
                'contenido' => 'nullable|string',
            ]);

            $version_propuesta->update($request->all());

            return response()->json($version_propuesta);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al editar' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }


    public function cambiarEstadoVersion(Request $request, $id) // id de la propuesta
    {
        try {
            $propuesta = Propuesta::find($id);

            if (!$propuesta) {
                //return response()->json(['message' => 'Servicio no encontrado'], 404);
                throw new \Exception('Propuesta no encontrada', 404);
            }

            $request->validate([
                'id_version_propuesta' => 'required|integer',
            ]);

            $version_propuesta = VersionPropuesta::find($request->id_version_propuesta);

            if (!$version_propuesta) {
                throw new \Exception('Versión de propuesta no encontrada', 404);
            }
            // lo que hace cambiar el estado de "id_version_propuesta" a "en edicion" y deja a todos los demas en "no edicion"
            
            $resultado = DB::select('CALL cambiar_estado_version_propuesta(?, ?)', [
                $id, // idPropuestaIn
                $request->id_version_propuesta // idVersionIn
            ]);

            // Aqui va el llamado al procedimiento
            return response()->json([
                'mensaje' => 'Se cambio el estado de la version a En edicion',
                'success' => 200
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    public function obtenerVersionEnEdicion($id) // id de la propuesta
    {
        try {
            $propuesta = Propuesta::find($id);

            if (!$propuesta) {
                //return response()->json(['message' => 'Servicio no encontrado'], 404);
                throw new \Exception('Propuesta no encontrada', 404);
            }

            // Busca la versión que está en edición
            $versionEnEdicion = $propuesta->versiones()->where('en_edicion', true)->first();

            if (!$versionEnEdicion) {
                throw new \Exception('No hay versión en edición', 404);
            }

            return response()->json($versionEnEdicion);


        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    public function obtenerVersionPublicada($id) // id de la propuesta
    {
        try {
            $propuesta = Propuesta::find($id);

            if (!$propuesta) {
                //return response()->json(['message' => 'Servicio no encontrado'], 404);
                throw new \Exception('Propuesta no encontrada', 404);
            }

            // Busca la versión que está en edición
            $versionPubulicada = $propuesta->version_publicada;

            if (!$versionPubulicada) {
                throw new \Exception('La propuesta aun no tiene una version publicada', 404);
            }

            $versionPropuestaPublicada = VersionPropuesta::find($versionPubulicada);

            if (!$versionPropuestaPublicada) {
                throw new \Exception('La version publicada fue modificada o no existe.', 404);
            }

            return response()->json($versionPropuestaPublicada);


        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No encontrado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }
}
