<?php

namespace App\Models;

use Yajra\Oci8\Eloquent\OracleEloquent;

class Employee extends OracleEloquent
{
    protected $table = 'empleadosnomina';
}