<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios'; // Especificar la tabla si no sigue la convención

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena_hash',
        'id_rol',
    ];
    public $timestamps = false; // Desactivar timestamps
    // Relación con el modelo Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
}
