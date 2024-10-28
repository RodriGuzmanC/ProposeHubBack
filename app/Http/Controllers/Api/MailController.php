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
        ]);

        $mail = new PHPMailer(true);

        try {
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
            $mail->addAddress($request->input('to'));

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
