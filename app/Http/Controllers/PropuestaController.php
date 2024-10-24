<?php

namespace App\Http\Controllers;

use App\Models\Propuesta;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\Organizacion;
use App\Models\Plantilla; // Asegúrate de importar el modelo
use App\Models\EstadoPropuesta; // Asegúrate de importar el modelo
use Illuminate\Http\Request;

class PropuestaController extends Controller
{
    // Mostrar todas las propuestas
    public function index()
    {
        $propuestas = Propuesta::with('estado')->get(); // Obtener todas las propuestas con su estado
        return view('dashboard.propuestas.index', compact('propuestas')); // Vista para mostrar propuestas
    }

    // Mostrar el formulario para crear una nueva propuesta
    public function create()
{
    $plantillas = Plantilla::all();
    $clientes = Cliente::all(); // Obtener todos los clientes
    $estados = EstadoPropuesta::all(); // Obtener todos los estados
    return view('dashboard.propuestas.create', compact('clientes', 'estados','plantillas')); // Pasa ambos a la vista
}


    // Manejar el almacenamiento de una nueva propuesta
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'monto' => 'required|numeric',
            'id_estado' => 'required|exists:estado_propuestas,id',
            // Agrega otras validaciones según sea necesario
        ]);

        Propuesta::create($request->all());

        return redirect()->route('propuestas.index'); // Redirigir a la lista de propuestas
    }

    // Mostrar una propuesta específica
    public function show($id)
    {
        $propuesta = Propuesta::with('estado')->findOrFail($id); // Cargar el estado también
        return view('dashboard.propuestas.show', compact('propuesta'));
    }

    // Mostrar el formulario para editar una propuesta
    public function edit($id)
    {
        $propuesta = Propuesta::findOrFail($id);
        $estados = EstadoPropuesta::all(); // Obtener todos los estados para el formulario de edición
        return view('dashboard.propuestas.edit', compact('propuesta', 'estados'));
    }

    // Manejar la actualización de una propuesta
    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'monto' => 'required|numeric',
            'id_estado' => 'required|exists:estado_propuestas,id',
            // Agrega otras validaciones según sea necesario
        ]);

        $propuesta = Propuesta::findOrFail($id);
        $propuesta->update($request->all());

        return redirect()->route('propuestas.index'); // Redirigir a la lista de propuestas
    }

    // Manejar la eliminación de una propuesta
    public function destroy($id)
    {
        $propuesta = Propuesta::findOrFail($id);
        $propuesta->delete();

        return redirect()->route('propuestas.index'); // Redirigir a la lista de propuestas
    }
    // Métodos en el controlador
public function showPlantillas()
{
    return view('dashboard.propuestas.plantillas');
}

public function showContacto(Request $request)
{
    $clientes = Cliente::all(); // Obtener todos los clientes
    $organizaciones = Organizacion::all(); // Obtener todas las organizaciones
    return view('dashboard.propuestas.contacto', compact('clientes'));
}

public function showServicio(Request $request)
{
    $servicios = Servicio::all(); // Obtener todos los servicios
    return view('dashboard.propuestas.servicio', compact('servicios'));
}


public function showInformacion(Request $request)
{
    return view('dashboard.propuestas.informacion')->with('template', $request->get('template'));
}
}
