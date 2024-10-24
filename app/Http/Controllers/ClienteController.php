<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Organizacion;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        return redirect()->route('contactos.index');
    }

    
public function create()
{
    $organizaciones = Organizacion::all(); // Recupera todas las organizaciones
    return view('dashboard.clientes.create', compact('organizaciones'));
}


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'id_organizacion' => 'nullable|exists:organizaciones,id',
        ]);

        Cliente::create($request->all());
        return redirect()->route('contactos.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('dashboard.clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
            'id_organizacion' => 'nullable|exists:organizaciones,id',
        ]);

        $cliente->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}

