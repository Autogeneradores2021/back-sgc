<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Yajra\Oci8\Eloquent\OracleEloquent;

class Employee extends OracleEloquent
{
    protected $table = 'empleadosnomina';

    static public function getUsers() {
        $employees = [];
        // $employees = Employee::query()->where('estado', 'ACTIVO')->whereNotNull('correo')->orderBy('codigo', 'asc')->get();
        foreach ($employees as $employee) {
            Selectable::createIfNotExist('areas', $employee->division, $employee->division);
            Selectable::createIfNotExist('positions', $employee->cargo, $employee->cargo);
            if ($user = User::query()->where('identification_number', strtolower($employee->codigo))->first()) {
                $user->name = $employee->nombre;
                $user->position_code = $employee->cargo;
                $user->area_code = $employee->division;
            } else {
                $user = new User([
                    'name' => $employee->nombre,
                    'email' => strtolower($employee->correo),
                    'role_code' => 'USER',
                    'area_code' => $employee->division,
                    'position_code' => $employee->cargo,
                    'identification_type' => 'CC',
                    'identification_number' => $employee->codigo,
                ]); 
                $user->password = Hash::make(User::generateRandomPassword());
            }
            $user->save();
            print("Usuario ".$employee->nombre." OK\r\n");
        }
    }
}