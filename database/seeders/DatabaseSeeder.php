<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Selectable;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        ],
        'detected_places' => [
            [ 'code' => 'BOTE', 'description' => 'El bote' ],
            [ 'code' => 'SAIRE', 'description' => 'El saire' ],
        ],
        'unfulfilled_requirements' => [
            [ 'code' => 'ISO', 'description' => 'ISO 270001' ],
            [ 'code' => 'AMB', 'description' => 'Ambiental' ],
        ],
        'affected_processes' => [
            [ 'code' => 'FAC', 'description' => 'Facturacion' ],
            [ 'code' => 'PER', 'description' => 'Perdidas' ],
            [ 'code' => 'DIS', 'description' => 'Distribucion' ],
            [ 'code' => 'SUS', 'description' => 'Suspension' ],
        ],
        'detection_types' => [
            [ 'code' => 'FIS', 'description' => 'Fisica' ],
            [ 'code' => 'DIS', 'description' => 'A distancia' ],
        ],
        'action_types' => [
            [ 'code' => 'PROBLEM', 'description' => 'Hallazgo' ],
            [ 'code' => 'SUGGEST', 'description' => 'Mejora' ],
        ],
        # Upgrade plans
        'upgrade_plan_types' => [
            [ 'code' => 'INM', 'description' => 'Inmediato' ],
            [ 'code' => 'DEF', 'description' => 'Permanente' ],
        ],
        # Tracking Isuss
        'icons' => [
            [ 'code' => 'INFO', 'description' => 'heroicons_outline:information-circlead' ],
        ],
        # Finish request
        'result_types' => [
            [ 'code' => 'OK', 'description' => 'Cierre' ],
            [ 'code' => 'SUB', 'description' => 'Requiere subsanacion' ],
        ],
        # users
        'areas' => [
            [ 'code' => 'FAC', 'description' => 'Facturacion' ],
            [ 'code' => 'PER', 'description' => 'Perdidas' ],
            [ 'code' => 'DIS', 'description' => 'Distribucion' ],
            [ 'code' => 'SUS', 'description' => 'Suspension' ],
            [ 'code' => 'SYS', 'description' => 'Sistemas' ],
            [ 'code' => 'SGC', 'description' => 'Sistema de gestion de calidad' ],
            [ 'code' => 'SCI', 'description' => 'Sistema de control interno' ],
        ],
        'positions' => [
            [ 'code' => 'LEAD', 'description' => 'Lider' ],
            [ 'code' => 'AUD', 'description' => 'Auditor' ],
            [ 'code' => 'P1', 'description' => 'Profesional 1' ],
            [ 'code' => 'P2', 'description' => 'Profesional 2' ],
            [ 'code' => 'P3', 'description' => 'Profesional 3' ],
            [ 'code' => 'P4', 'description' => 'Profesional 4' ],
            [ 'code' => 'P5', 'description' => 'Profesional 5' ],
        ],
        'identification_types' => [
            [ 'code' => 'CC', 'description' => 'Cedula de ciudadania' ],
            [ 'code' => 'CE', 'description' => 'Cedula de extranjeria' ],
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
                try {
                    $model = new Selectable($value, $table);
                    $model->save();
                } catch (QueryException $th) {
                    print('Error');
                }
            }
        }
        Role::create([
            [ 'code' => 'ADMIN', 'name' => 'Administrador'],
            [ 'code' => 'AUDITOR', 'name' => 'Auditor'],
            [ 'code' => 'USER', 'name' => 'Usuario'],
        ]);
        User::insert([
            ['email' => 'alexander.cruz@electrohuila.com', 'password' => Hash::make('12345678'), 'name' => 'Alexander Cruz', 'role_code' => 'ADMIN', 'position_code' => 'LEAD', 'area_code' => 'SGC'],
            ['email' => 'diego.palacios@electrohuila.com', 'password' => Hash::make('12345678'), 'name' => 'Diego Palacios', 'role_code' => 'ADMIN', 'position_code' => 'LEAD', 'area_code' => 'SYS'],
            ['email' => 'leidy.bernate@electrohuila.com', 'password' => Hash::make('12345678'), 'name' => 'Leidy Bernate', 'role_code' => 'ADMIN', 'position_code' => 'LEAD', 'area_code' => 'SCI'],
            ['email' => 'hector.coronado@electrohuila.com', 'password' => Hash::make('12345678'), 'name' => 'Hector Coronado', 'role_code' => 'ADMIN', 'position_code' => 'P3', 'area_code' => 'SYS'],
            ['email' => 'auditor1@electrohuila.com', 'password' => Hash::make('12345678'), 'name' => 'Auditor 1', 'role_code' => 'AUDITOR', 'position_code' => 'AUD', 'area_code' => 'SGC'],
            ['email' => 'oscar.ruiz@electrohuila.com', 'password' => Hash::make('12345678'), 'name' => 'Oscar Ruiz', 'role_code' => 'USER', 'position_code' => 'P2', 'area_code' => 'SYS'],
        ]);
    }
}
