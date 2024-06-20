<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    use HasFactory;
    protected $table = 'productos';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'cantidad',
        'vendidos',
        'id_tienda',
        'id_categoria',
        'id_usuario_venta',
        'id_usuario_creacion',
        'fecha_venta'
    ];
}
