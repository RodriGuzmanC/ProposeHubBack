<?php

namespace App\Http\Controllers;

use App\Models\Organizacion; // Asegúrate de que este modelo exista
use App\Models\Cliente; // Importa el modelo Cliente
use Illuminate\Http\Request;

class ContactosController extends Controller
{
    public function index()
    {
        // Obtener todas las organizaciones
        $organizaciones = Organizacion::all();

        // Obtener todos los clientes
        $clientes = Cliente::all();

        return view('dashboard.contactos.index', compact('organizaciones', 'clientes'));
    }

    // Aquí puedes agregar más métodos si es necesario
}
