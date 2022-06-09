<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $fillable = ['nombre_empresa' , 'rfc' , 'telefono' , 'domicilio' ];
    use HasFactory;
}
