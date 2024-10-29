<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes'; // Nombre de la tabla

    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'id_organizacion',
        'contrasena_hash',
    ];

    public function organizacion()
    {
        return $this->belongsTo(Organizacion::class, 'id_organizacion');
    }
}
