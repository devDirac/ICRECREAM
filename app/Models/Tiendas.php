<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiendas extends Model
{
    use HasFactory;
    protected $table = 'tiendas';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'ubicacion_direccion',
        'lat',
        'lon'
    ];
}
