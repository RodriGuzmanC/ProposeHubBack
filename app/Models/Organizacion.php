<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizacion extends Model
{
    use HasFactory;

    protected $table = 'organizaciones'; // Nombre de la tabla

    protected $fillable = [
        'nombre',
        'telefono',
        'correo',
    ];

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_organizacion');
    }
}
