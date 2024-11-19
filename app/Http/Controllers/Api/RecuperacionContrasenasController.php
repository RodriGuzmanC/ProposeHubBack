<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\RecuperacionContrasena;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;

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
            'updated_at' => $recuperacion->updated_at,
        ]);
    }

    public function regenerarToken(Request $request)
    {
        // Validar que se haya proporcionado un correo
        $request->validate([
            'correo' => 'required|email',
        ]);

        // Buscar el usuario por correo
        $usuario = Usuario::where('email', $request->correo)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Buscar si el usuario ya tiene un token de recuperación
        $recuperacion = RecuperacionContrasena::where('id_usuario', $usuario->id)->first();

        // Si el token ya existe y ha pasado más de una hora, regeneramos el token
        if ($recuperacion && Carbon::now()->greaterThan(Carbon::parse($recuperacion->updated_at)->addHour())) {
            // Generar un nuevo token
            $nuevoToken = Str::random(60); // Puedes usar un algoritmo más robusto si lo prefieres

            // Actualizar el token en la base de datos
            $recuperacion->token = $nuevoToken;
            $recuperacion->updated_at = Carbon::now();
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
        $mail->addAddress($usuario->email);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Recuperacion de contraseña';
        $mail->Body    = 'ingresa a este link: https://a54d56as.com/$nuevoToken';

        $mail->send();


        return response()->json([
            'message' => 'El token ha sido regenerado y enviado al correo.',
            'token' => $nuevoToken
        ]);
    }
}
