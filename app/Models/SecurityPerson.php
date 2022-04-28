<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityPerson extends Model
{

    protected $connection = 'security';

    protected $table = 'persons';
}