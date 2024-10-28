<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersionPropuesta extends Model
{
    use HasFactory;

    protected $table = 'versiones_propuestas'; // Especificar la tabla si no sigue la convención

    protected $fillable = [
        'id_propuesta',
        'contenido',
        'en_edicion',
    ];
    public $timestamps = false; // Desactivar timestamps
    // Relación con el modelo Propuesta
    public function propuesta()
    {
        return $this->belongsTo(Propuesta::class, 'id_propuesta');
    }
}
