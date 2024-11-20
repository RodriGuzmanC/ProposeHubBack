<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RecuperacionContrasena;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Hash;

class RecuperacionContrasenasController extends Controller
{
    public function obtenerToken(Request $request, $token)
    {
        // Buscar el token en la base de datos
        $recuperacion = RecuperacionContrasena::where('token', $token)->first();

        if (!$recuperacion) {
            return response()->json(['error' => 'Token no encontrado'], 404);
        }

        // Verificar si han pasado más de una hora desde la última actualización
        $horaLimite = Carbon::parse($recuperacion->updated_at)->addHour();

        if (Carbon::now()->greaterThan($horaLimite)) {
            return response()->json(['error' => 'El token ha expirado'], 400);
        }

        // Si el token es válido, devolverlo
        return response()->json([
            'message' => 'Token válido',
            'token' => $recuperacion->token,
            'id_usuario' => $recuperacion->id_usuario,
            'updated_at' => $recuperacion->updated_at,
        ]);
    }

    // Para cambiar la contrasena
    public function cambiarContrasena(Request $request)
    {
        try {
            
            // Validación de los datos
            $request->validate([
                'id_usuario' => 'required',
                'contrasena_nueva' => 'required|string',
            ]);

            $usuario = Usuario::find($request->id_usuario);

            if (!$usuario) {
                //return response()->json(['mensaje' => 'Usuario no encontrado.'], 404);
                throw new \Exception('Usuario no encontrado', 404);
            }

            // Actualizar la contraseña con la nueva
            $usuario->contrasena_hash = Hash::make($request->contrasena_nueva);
            $usuario->save();

            // Redirigir al usuario con un mensaje de éxito
            return response()->json(['mensaje' => 'La contraseña ha sido actualizada']);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'Ocurrio un error al momento de cambiar la contraseña' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }

    public function regenerarToken(Request $request)
    {
        try {
            // Validar que se haya proporcionado un correo
            $request->validate([
                'correo' => 'required|email',
                'recuperar_ruta' => 'required|string',
            ]);

            // Buscar el usuario por correo
            $usuario = Usuario::where('correo', $request->correo)->first();

            if (!$usuario) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            // Buscar si el usuario ya tiene un token de recuperación
            $recuperacion = RecuperacionContrasena::where('id_usuario', $usuario->id)->first();

            // Si el token ya existe y ha pasado más de una hora, regeneramos el token
            if ($recuperacion) {
                // Generar un nuevo token
                $nuevoToken = Str::random(60); // Puedes usar un algoritmo más robusto si lo prefieres

                // Actualizar el token en la base de datos
                $recuperacion->token = $nuevoToken;
                $recuperacion->save();
            } else {
                // Si no existe un token o no ha pasado una hora, generamos uno nuevo
                $nuevoToken = Str::random(60); // Generar un token aleatorio
                RecuperacionContrasena::create([
                    'id_usuario' => $usuario->id,
                    'token' => $nuevoToken,
                ]);
            }

            // Enviar el token al correo del usuario
            // Asumimos que tienes una función para enviar el correo
            $mail = new PHPMailer(true);

            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cambia esto por tu servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'eduis.carranza123@gmail.com'; // Tu correo
            $mail->Password = 'ovwh ekbw sdaa tgam'; // Tu contraseña
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587; // O 465 para SSL

            // Destinatarios
            $mail->setFrom('eduis.carranza123@gmail.com', 'Eduis Guzman');
            $mail->addAddress($usuario->correo);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Instrucciones para recuperar tu contraseña';
            $mail->Body = '
                <html>
                    <head>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                color: #333;
                                line-height: 1.6;
                            }
                            .container {
                                padding: 20px;
                                border: 1px solid #ddd;
                                border-radius: 5px;
                                background-color: #f9f9f9;
                            }
                            .header {
                                font-size: 20px;
                                font-weight: bold;
                                margin-bottom: 10px;
                            }
                            .content {
                                margin-bottom: 20px;
                            }
                            .button {
                                display: inline-block;
                                padding: 10px 20px;
                                background-color: #007bff;
                                color: white;
                                text-decoration: none;
                                border-radius: 5px;
                                font-weight: bold;
                            }
                            .footer {
                                font-size: 12px;
                                color: #777;
                                margin-top: 20px;
                            }
                                a[href]{
                                    color: #fff !important;
                                }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">Recuperación de contraseña</div>
                            <div class="content">
                                <p>Hola,</p>
                                <p>Hemos recibido una solicitud para recuperar tu contraseña. Si fuiste tú, por favor haz clic en el siguiente enlace para crear una nueva contraseña:</p>
                                <a href="' . $request->recuperar_ruta . '' . $nuevoToken . '" class="button">Recuperar mi contraseña</a>
                                <p>Este enlace expirará en 24 horas por razones de seguridad.</p>
                            </div>
                            <div class="footer">
                                <p>Si no solicitaste la recuperación de tu contraseña, por favor ignora este correo.</p>
                                <p>Si necesitas ayuda adicional, no dudes en contactarnos.</p>
                            </div>
                        </div>
                    </body>
                </html>
            ';

            $mail->send();


            return response()->json([
                'message' => 'El token ha sido regenerado y enviado al correo.',
                'token' => $nuevoToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'error' => $e->getCode() === 404 ? 'No regitrado' : 'Error del servidor'
            ], $e->getCode() === 404 ? 404 : 500);
        }
    }
}
