<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propuesta extends Model
{
    use HasFactory;

    protected $table = 'propuestas'; // Especificar la tabla si no sigue la convención

    protected $fillable = [
        'titulo',
        'monto',
        'id_estado',
        'id_plantilla',
        'id_servicio',
        'id_cliente',
        'informacion',
        'id_usuario', // Asegúrate de incluir esto
    ];
    public function estado()
    {
        return $this->belongsTo(EstadoPropuesta::class, 'id_estado');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function organizacion()
    {
        return $this->hasOneThrough(Organizacion::class, Cliente::class, 'id', 'id', 'id_cliente', 'id_organizacion');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
    public function plantilla()
{
    return $this->belongsTo(Plantilla::class, 'id_plantilla');
}

public function servicio()
{
    return $this->belongsTo(Servicio::class, 'id_servicio');
}
}

