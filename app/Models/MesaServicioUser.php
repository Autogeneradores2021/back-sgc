<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MesaServicioUser extends Model
{

    protected $connection = 'mesa_servicio';

    protected $table = 'usuarios';
}