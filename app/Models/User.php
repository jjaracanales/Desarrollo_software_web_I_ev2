<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    protected $fillable = [
        'nombre',
        'correo',
        'clave'
    ];

    protected $hidden = [
        'clave'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Hash de la clave antes de guardar
     */
    public function setClaveAttribute($value)
    {
        $this->attributes['clave'] = Hash::make($value);
    }

    /**
     * Verificar si la clave coincide
     */
    public function verificarClave($clave)
    {
        return Hash::check($clave, $this->clave);
    }

    /**
     * Relación con proyectos
     */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'created_by');
    }
}
