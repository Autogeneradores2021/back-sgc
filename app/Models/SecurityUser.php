<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SecurityUser extends Model
{

    protected $connection = 'security';

    protected $table = 'users';

    static public function getUsers()
    {
        $lastUserCreated = User::query()->where('role_code', '<>', 'EXTERNO')->orderBy('created_at', 'DESC')->first();
        $lastUserCreated = $lastUserCreated->created_at;
        $lastUserCreated = date('Y-m-d', strtotime($lastUserCreated));
        $query = SecurityUser::query()->whereDate('created_at', '>', $lastUserCreated)->get();
        Log::info('LA CONSULTA ARROJO '.$query->count().' REUSLTADOS BASADO EN LA FECHA DE BUSQUEDA '.$lastUserCreated);
        if ($query->count() != 0) {
            foreach ($query as $user) {
                $actions = [];
                $person = SecurityPerson::query()->where('id', $user->person_id)->first();
                $usuarioMesaServicio = MesaServicioUser::query()->where('user_id', $user->id)->first();
                $area = $usuarioMesaServicio ? MesaServicioArea::query()->where('id', $usuarioMesaServicio->area_id)->first() : null;
                $area_code = $area ? Selectable::createIfNotExist('areas', $area->nombre, $area->nombre) : 'EXTERNO';
                $employee = Employee::query()->where('estado', 'ACTIVO')->where('codigo', $person->document_number)->orderBy('codigo', 'desc')->first();
                $position_code = Selectable::createIfNotExist('positions', 'EN_MISION', 'EN MISION');
                if ($employee) {
                    Selectable::createIfNotExist('areas', $employee->division, $employee->division);
                    Selectable::createIfNotExist('positions', $employee->cargo, $employee->cargo);
                    $area_code = $employee->division;
                    $position_code = $employee->cargo;
                }
                $user = User::query()->where(['identification_number' => $person->document_number])->first();
                if (!$user) { $user = User::query()->where(['email' => strtolower($person->email)])->first(); }
                Log::info('CONEXION CON SEGURIDAD TRANSVERSAL CONFIRMADA');
                if ($user) {
                    Log::info('EXISTE REGISTRO DE USUARIO');
                    $area_code = $area_code;
                    $position_code = $user->position_code;
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
