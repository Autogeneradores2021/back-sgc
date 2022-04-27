<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SecurityUser extends Model
{

    protected $connection = 'security';

    protected $table = 'user';

    static public function getUsers()
    {
        $lastUserCreated = User::query()->where('role_code', '<>', 'EXTERNO')->orderBy('created_at', 'DESC')->first();
        $lastUserCreated = $lastUserCreated->created_at;
        $query = SecurityUser::query()->where('created_at', $lastUserCreated)->get();
        if ($query->count() != 0) {
            foreach ($query as $user) {
                $person = SecurityPerson::query()->where('id', $user->person_id)->first();
                $actions = [];
                $user = User::query()->where(['identification_number' => $person->document_number])->first();
                Log::info('CONEXION CON SEGURIDAD TRANSVERSAL CONFIRMADA');
                if ($user) {
                    Log::info('EXISTE REGISTRO DE USUARIO');
                    $area_code = $user->area_code;
                    $position_code = $user->position_code;
                    $employee = Employee::query()->where('estado', 'ACTIVO')->where('codigo', $person->document_number)->orderBy('codigo', 'desc')->first();
                    if ($employee) {
                        Selectable::createIfNotExist('areas', $employee->division, $employee->division);
                        Selectable::createIfNotExist('positions', $employee->cargo, $employee->cargo);
                        $area_code = $employee->division;
                        $position_code = $employee->cargo;
                    }
                    if ($area_code == 'EXTERNO' && $person->contractor_company) {
                        $area_code = Selectable::createIfNotExist('areas', $person->contractor_company, $person->contractor_company);
                    }
                    $user->phone_number = $person->cellphone ? $person->cellphone : $person->telephone;
                    $user->state_code = $person->state == 1 ? 'ACTIVE' : 'DISABLE';
                    $user->password = Hash::make(User::generateRandomPassword());
                    $user->area_code = $area_code;
                    $user->position_code = $position_code;
                    $user->email = strtolower($person->email);
                    $user->save();
                    Log::info('USUARIO ACTUALIZADO');
                    Log::info($user);
                } else {
                    Log::info('NO EXISTE REGISTRO DE USUARIO');
                    $employee = Employee::query()->where('estado', 'ACTIVO')->where('codigo', $person->document_number)->orderBy('codigo', 'desc')->first();
                    $area_code = 'EXTERNO';
                    $position_code = 'EXTERNO';
                    if ($employee) {
                        Selectable::createIfNotExist('areas', $employee->division, $employee->division);
                        Selectable::createIfNotExist('positions', $employee->cargo, $employee->cargo);
                        $area_code = $employee->division;
                        $position_code = $employee->cargo;
                    }
                    if ($area_code == 'EXTERNO' && $person->contractor_company) {
                        $area_code = Selectable::createIfNotExist('areas', $person->contractor_company, $person->contractor_company);
                    }
                    $user = new User([
                        'name' => strtoupper($person->first_name . ' ' . $person->second_name . ' ' . $person->first_lastname . ' ' . $person->second_lastname),
                        'email' => strtolower($person->email),
                        'phone_number' => $person->cellphone ? $person->cellphone : $person->telephone,
                        'identification_type' => $person->document_type_id == 1 ? 'CC' : 'NIT',
                        'identification_number' => $person->document_number,
                        'role_code' => 'USER',
                        'area_code' => $area_code,
                        'position_code' => $position_code,
                    ]);
                    $user->state_code = $person->state == 1 ? 'ACTIVE' : 'DISABLE';
                    $user->password = Hash::make(User::generateRandomPassword());
                    $user->save();
                    Log::info('USUARIO REGISTRADO');
                    Log::info($user);
                }
                $user->actions = json_encode($actions);
                $user->save();
            }
        }
        return $lastUserCreated;
    }
}
