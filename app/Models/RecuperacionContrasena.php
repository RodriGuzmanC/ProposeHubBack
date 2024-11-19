<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecuperacionContrasena extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla si no sigue la convención plural
    protected $table = 'recuperacion_contrasenas';

    // Especificar los campos que se pueden llenar (mass assignable)
    protected $fillable = [
        'id_usuario',
        'token',
        'created_at',
        'updated_at',
    ];

    // Especificar que no se deben gestionar automáticamente los campos 'created_at' y 'updated_at' si no lo deseas
    // protected $timestamps = false; // Descomenta si no quieres que Laravel gestione automáticamente estos campos

    // Si necesitas manipular otros campos, puedes agregarlos a la propiedad $dates
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Si tienes relaciones con otros modelos, puedes definirlas aquí, por ejemplo:
    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
