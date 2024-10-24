<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;
    
    protected $table = 'servicios'; // Especificar la tabla

    protected $fillable = [
        'nombre',
        'descripcion',
    ];
    
    public $timestamps = false;
}
