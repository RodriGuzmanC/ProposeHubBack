<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    use HasFactory;

    protected $table = 'plantillas'; // Opcional, solo si el nombre de la tabla no sigue la convención
    protected $fillable = [
        'nombre',
        'categoria',
        'descripcion',
        'contenido',
        'is_active',
    ];
}
