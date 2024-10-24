<?php

namespace App\Http\Controllers;

use App\Models\Plantilla;
use Illuminate\Http\Request;
use PDF; // Asegúrate de importar la clase PDF


class PlantillaController extends Controller
{
    public function index()
    {
        $plantillas = Plantilla::all();
        return view('dashboard.plantillas.index', compact('plantillas'));
    }

    public function create()
    {
        return view('dashboard.plantillas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contenido' => 'nullable|string',
        ]);

        // Obtener solo el texto plano del contenido
        $contenidoTexto = strip_tags($request->input('contenido'));
    
        Plantilla::create([
            'nombre' => $request->input('nombre'),
            'contenido' => $contenidoTexto,
        ]);        
        return redirect()->route('plantillas.index')->with('success', 'Plantilla creada con éxito.');
    }

    public function show(Plantilla $plantilla)
    {
        return view('dashboard.plantillas.show', compact('plantilla'));
    }

    public function edit(Plantilla $plantilla)
    {
        return view('dashboard.plantillas.edit', compact('plantilla'));
    }

    public function update(Request $request, Plantilla $plantilla)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'contenido' => 'nullable|string',
        ]);

        $plantilla->update($request->all());
        return redirect()->route('plantillas.index')->with('success', 'Plantilla actualizada con éxito.');
    }

    public function destroy(Plantilla $plantilla)
    {
        $plantilla->delete();
        return redirect()->route('plantillas.index')->with('success', 'Plantilla eliminada con éxito.');
    }
    public function download(Plantilla $plantilla)
{
    return view('dashboard.plantillas.download', compact('plantilla'));
}

    public function downloadPDF(Plantilla $plantilla)
    {
        $pdf = PDF::loadHTML($plantilla->contenido); // Cargar el contenido HTML
        return $pdf->download("plantilla_{$plantilla->id}.pdf"); // Descargar el PDF
    }
}
