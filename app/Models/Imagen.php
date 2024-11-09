<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    use HasFactory;

    protected $table = 'imagenes';

    // Campos que se pueden llenar con mass assignment
    protected $fillable = [
        'id_usuario',
        'nombre',
        'path',
    ];

    /**
     * Relación con el modelo Usuarios.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
