<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use Illuminate\Http\Request;

class OrganizacionController extends Controller
{
    // Método para listar todas las organizaciones
    public function index()
{
    return redirect()->route('contactos.index');
}


    // Método para mostrar una organización específica
    public function show($id)
    {
        $organizacion = Organizacion::find($id);
        return view('organizaciones.show', compact('organizacion')); // Vista para mostrar detalles de una organización
    }

    // Método para mostrar el formulario de creación
    public function create()
{
    return view('dashboard.organizaciones.create');
}

    // Método para almacenar una nueva organización
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|string|email|max:255',
        ]);

        Organizacion::create($request->all());
        // Redirigir a la vista de contactos después de crear la organización
    return redirect()->route('contactos.index')->with('success', 'Organización creada exitosamente.');
    }

    // Método para mostrar el formulario de edición
    public function edit($id)
    {
        $organizacion = Organizacion::find($id);
        return view('dashboard.organizaciones.edit', compact('organizacion'));

    }

    // Método para actualizar una organización existente
    public function update(Request $request, $id)
    {
        $organizacion = Organizacion::find($id);
        if (!$organizacion) {
            return redirect()->route('organizaciones.index')->with('error', 'Organizacion no encontrada'); // Maneja el error
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|string|email|max:255',
        ]);

        $organizacion->update($request->all());
        return redirect()->route('organizaciones.index')->with('success', 'Organizacion actualizada correctamente');
    }

    // Método para eliminar una organización
    public function destroy($id)
    {
        $organizacion = Organizacion::find($id);
        if (!$organizacion) {
            return redirect()->route('organizaciones.index')->with('error', 'Organizacion no encontrada'); // Maneja el error
        }

        $organizacion->delete();
        return redirect()->route('organizaciones.index')->with('success', 'Organizacion eliminada correctamente');
    }
}
