<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPropuesta extends Model
{
    use HasFactory;

    protected $table = 'estado_propuestas'; // Nombre de la tabla

    protected $fillable = [
        'nombre',
    ];

    public $timestamps = false; // Si no estás usando timestamps 'created_at' y 'updated_at'
}
