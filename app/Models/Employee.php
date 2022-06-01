<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\Oci8\Eloquent\OracleEloquent;

class Employee extends OracleEloquent
{
    protected $table = 'empleadosnomina';


    static public function getUsers() {
        $dayAgo = 1;
        $dayToCheck = Carbon::now()->subDays($dayAgo);
        $employees = Employee::query()->where('estado', 'ACTIVO')->whereDate('fecha_inicial', '>', $dayToCheck)->orderBy('codigo', 'asc')->get();
        Log::info('LA CONSULTA ARROJO '.count($employees).' REUSLTADOS BASADO EN LA FECHA DE BUSQUEDA '.$dayToCheck);
        foreach ($employees as $employee) {
            $email = $employee->correo;
            if (!$email) {
                $email = User::generateRandomPassword().'@no-email.com';
            }
            Selectable::createIfNotExist('areas', $employee->division, $employee->division);
            Selectable::createIfNotExist('positions', $employee->cargo, $employee->cargo);
            if ($user = User::query()->where('identification_number', strtolower($employee->codigo))->first()) {
                $user->name = $employee->nombre;
                $user->position_code = $employee->cargo;
                $user->area_code = $employee->division;
            } else {
                $user = new User([
                    'name' => $employee->nombre,
                    'email' => strtolower($email),
                    'role_code' => 'NOMINA',
                    'area_code' => $employee->division,
                    'position_code' => $employee->cargo,
                    'identification_type' => 'CC',
                    'identification_number' => $employee->codigo,
                ]); 
                $user->password = Hash::make(User::generateRandomPassword());
            }
            $user->save();
            print("Nuevo usuario ".$employee->nombre." OK\r\n");
        }
    }

    static public function getAllUsers() {
        $employees = Employee::query()->where('estado', 'ACTIVO')->whereNotNull('correo')->orderBy('codigo', 'asc')->get();
        Log::info('LA CONSULTA ARROJO '.count($employees).' REUSLTADOS BASADO EN LA FECHA DE BUSQUEDA ');
        foreach ($employees as $employee) {
            $email = $employee->correo;
            if (!$email) {
                $email = User::generateRandomPassword().'@no-email.com';
            }
            Selectable::createIfNotExist('areas', $employee->division, $employee->division);
            Selectable::createIfNotExist('positions', $employee->cargo, $employee->cargo);
            if ($user = User::query()->where('identification_number', strtolower($employee->codigo))->first()) {
                $user->name = $employee->nombre;
                $user->position_code = $employee->cargo;
                $user->area_code = $employee->division;
            } else {
                $user = new User([
                    'name' => $employee->nombre,
                    'email' => strtolower($email),
                    'role_code' => 'NOMINA',
                    'area_code' => $employee->division,
                    'position_code' => $employee->cargo,
                    'identification_type' => 'CC',
                    'identification_number' => $employee->codigo,
                ]); 
                $user->password = Hash::make(User::generateRandomPassword());
            }
            $user->save();
            print("Nuevo usuario ".$employee->nombre." OK\r\n");
        }
    }
}