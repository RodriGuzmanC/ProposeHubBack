<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'to' => 'required|email',
            'subject' => 'required|string',
            'body' => 'required|string',
            'notification' => 'sometimes|boolean'
        ]);

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST'); // Cambia esto por tu servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME'); // Tu correo
            $mail->Password = env('MAIL_PASSWORD'); // Tu contraseña
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = env('MAIL_PORT'); // O 465 para SSL

            // Destinatarios
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($request->input('to'));
            if ($request->input('notification')) {
                $mail->addAddress(env('MAIL_USERNAME'));
            }

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $request->input('subject');
            $mail->Body    = $request->input('body');

            $mail->send();
            return response()->json(['message' => 'Correo enviado correctamente'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al enviar el correo: ' . $mail->ErrorInfo], 500);
        }
        
    }
}
