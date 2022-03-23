<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Role;
use App\Models\Selectable;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{

    public $selectables = [
        'request_types' => [
            [ 'code' => 'SGC', 'description' => 'Sistema de gestion de calidad' ],
            [ 'code' => 'SCI', 'description' => 'Sistema de control interno' ],
        ],
        # Request selectables
        'status' => [
            [ 'code' => 'PENDING', 'description' => 'Pendiente' ],
            [ 'code' => 'OPEN', 'description' => 'Abierto' ],
            [ 'code' => 'R_TO_CLOSE', 'description' => 'Listo para cierre' ],
            [ 'code' => 'CLOSE', 'description' => 'Cerrado' ],
            [ 'code' => 'EXPIRED', 'description' => 'Vencido' ],
            [ 'code' => 'TO_FIX', 'description' => 'Requiere subsanacion' ],
        ],
        'detected_places' => [
            [ 'code' => 'BOTE', 'description' => 'El bote', 'own_system' => false ],
            [ 'code' => 'SAIRE', 'description' => 'El saire', 'own_system' => false ],
        ],
        'unfulfilled_requirements' => [
            [ 'code' => 'ISO', 'description' => 'ISO 270001', 'own_system' => false ],
            [ 'code' => 'AMB', 'description' => 'Ambiental', 'own_system' => false ],
        ],
        'affected_processes' => [
            [ 'code' => 'FAC', 'description' => 'Facturacion', 'own_system' => false ],
            [ 'code' => 'PER', 'description' => 'Perdidas', 'own_system' => false ],
            [ 'code' => 'DIS', 'description' => 'Distribucion', 'own_system' => false ],
            [ 'code' => 'SUS', 'description' => 'Suspension', 'own_system' => false ],
        ],
        'detection_types' => [
            [ 'code' => 'FIS', 'description' => 'Fisica', 'own_system' => false ],
            [ 'code' => 'DIS', 'description' => 'A distancia', 'own_system' => false ],
        ],
        'action_types' => [
            [ 'code' => 'PROBLEM', 'description' => 'Hallazgo', 'own_system' => false ],
            [ 'code' => 'SUGGEST', 'description' => 'Mejora', 'own_system' => false ],
        ],
        # Upgrade plans
        'upgrade_plan_types' => [
            [ 'code' => 'INM', 'description' => 'Inmediato', 'own_system' => false ],
            [ 'code' => 'DEF', 'description' => 'Permanente', 'own_system' => false ],
        ],
        # Tracking Isuss
        'icons' => [
            [ 'code' => 'INFO', 'description' => 'heroicons_outline:information-circle', 'own_system' => false ],
            [ 'code' => 'NEW', 'description' => 'heroicons_outline:document-text', 'own_system' => false ],
            [ 'code' => 'WTEAM', 'description' => 'heroicons_outline:user-add', 'own_system' => false ],
            [ 'code' => 'UPLAN', 'description' => 'heroicons_outline:trending-up', 'own_system' => false ],
            [ 'code' => 'ANALYSIS', 'description' => 'heroicons_outline:search', 'own_system' => false ],
            [ 'code' => 'TRACKING', 'description' => 'heroicons_outline:tag', 'own_system' => false ],
            [ 'code' => 'FREQUEST', 'description' => 'heroicons_outline:check', 'own_system' => false ],
        ],
        # Finish request
        'result_types' => [
            [ 'code' => 'CLOSE', 'description' => 'Cierre' ],
            [ 'code' => 'TO_FIX', 'description' => 'Requiere subsanacion' ],
        ],
        # users
        'areas' => [
            [ 'code' => 'EXTERNO', 'description' => 'Area externa' ],
        ],
        'positions' => [
            [ 'code' => 'EXTERNO', 'description' => 'Persona externa' ],
        ],
        'identification_types' => [
            [ 'code' => 'CC', 'description' => 'Cedula de ciudadania' ],
            [ 'code' => 'CE', 'description' => 'Cedula de extranjeria' ],
        ],
        'states' => [
            [ 'code' => 'ACTIVE', 'description' => 'Activo' ],
            [ 'code' => 'DISABLE', 'description' => 'Inactivo' ],
            [ 'code' => 'EXPIRED', 'description' => 'Vencido' ],
            [ 'code' => 'UNKNOWN', 'description' => 'Desconocido' ],
        ],
        'designation_codes' => [
            [ 'code' => 'DZN', 'description' => 'Division zona norte' ],
            [ 'code' => 'DZC', 'description' => 'Division zona centro' ],
        ],
        'designation_groups' => [
            [ 'code' => 'H', 'description' => 'Hallazgo' ],
            [ 'code' => 'M', 'description' => 'Mejora' ],
        ],
        'designation_components' => [
            [ 'code' => 'AC', 'description' => 'Ambiental' ],
            [ 'code' => 'ER', 'description' => 'Evaluación del Riesgo' ],
            [ 'code' => 'CT', 'description' => 'Actividades de Control' ],
            [ 'code' => 'IC', 'description' => 'Información y Comunicación' ],
            [ 'code' => 'SM', 'description' => 'Supervisión y Monitoreo' ],
        ],
        
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->selectables as $table => $list) {
            foreach ($list as $value) {
                $model = new Selectable($value, $table);
                $model->save();
            }
            print("Seleccionable ".$table." OK\r\n");
        }
        Role::create([
            [ 'code' => 'ADMIN', 'name' => 'Administrador'],
            [ 'code' => 'AUDITOR', 'name' => 'Auditor'],
            [ 'code' => 'USER', 'name' => 'Usuario'],
        ]);
        print("Roles  OK\r\n");
        User::insert([
            ['email' => 'alexander.cruz@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Alexander Cruz', 'role_code' => 'ADMIN', 'position_code' => 'EXTERNO', 'area_code' => 'EXTERNO'],
            ['email' => 'diego.palacios@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Diego Palacios', 'role_code' => 'ADMIN', 'position_code' => 'EXTERNO', 'area_code' => 'EXTERNO'],
            ['email' => 'leidy.bernate@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Leidy Bernate', 'role_code' => 'ADMIN', 'position_code' => 'EXTERNO', 'area_code' => 'EXTERNO'],
            ['email' => 'hector.coronado@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Hector Coronado', 'role_code' => 'ADMIN', 'position_code' => 'EXTERNO', 'area_code' => 'EXTERNO'],
            ['email' => 'auditor1@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Auditor 1', 'role_code' => 'AUDITOR', 'position_code' => 'EXTERNO', 'area_code' => 'EXTERNO'],
            ['email' => 'oscar.ruiz@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Oscar Ruiz', 'role_code' => 'USER', 'position_code' => 'EXTERNO', 'area_code' => 'EXTERNO'],
        ]);
        print("Usuarios de prueba  OK\r\n");
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
                $user->password = Hash::make($this->generateRandomPassword());
            }
            $user->save();
            print("Usuario ".$employee->nombre." OK\r\n");
        }

        print("Usuarios de nomina  OK\r\n");
    }

    function generateRandomPassword($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
