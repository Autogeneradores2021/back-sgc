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
            [ 'code' => 'INFO', 'description' => 'heroicons_outline:information-circlead', 'own_system' => false ],
        ],
        # Finish request
        'result_types' => [
            [ 'code' => 'CLOSE', 'description' => 'Cierre' ],
            [ 'code' => 'TO_FIX', 'description' => 'Requiere subsanacion' ],
        ],
        # users
        'areas' => [
            [ 'code' => 'FAC', 'description' => 'Facturacion', 'own_system' => false ],
            [ 'code' => 'PER', 'description' => 'Perdidas', 'own_system' => false ],
            [ 'code' => 'DIS', 'description' => 'Distribucion', 'own_system' => false ],
            [ 'code' => 'SUS', 'description' => 'Suspension', 'own_system' => false ],
            [ 'code' => 'SYS', 'description' => 'Sistemas', 'own_system' => false ],
            [ 'code' => 'SGC', 'description' => 'Sistema de gestion de calidad', 'own_system' => false ],
            [ 'code' => 'SCI', 'description' => 'Sistema de control interno', 'own_system' => false ],
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
                $model = new Selectable($value, $table);
                $model->save();
            }
        }
        Role::create([
            [ 'code' => 'ADMIN', 'name' => 'Administrador'],
            [ 'code' => 'AUDITOR', 'name' => 'Auditor'],
            [ 'code' => 'USER', 'name' => 'Usuario'],
        ]);
        User::insert([
            ['email' => 'alexander.cruz@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Alexander Cruz', 'role_code' => 'ADMIN', 'position_code' => 'LEAD', 'area_code' => 'SGC'],
            ['email' => 'diego.palacios@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Diego Palacios', 'role_code' => 'ADMIN', 'position_code' => 'LEAD', 'area_code' => 'SYS'],
            ['email' => 'leidy.bernate@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Leidy Bernate', 'role_code' => 'ADMIN', 'position_code' => 'LEAD', 'area_code' => 'SCI'],
            ['email' => 'hector.coronado@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Hector Coronado', 'role_code' => 'ADMIN', 'position_code' => 'P3', 'area_code' => 'SYS'],
            ['email' => 'auditor1@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Auditor 1', 'role_code' => 'AUDITOR', 'position_code' => 'AUD', 'area_code' => 'SGC'],
            ['email' => 'oscar.ruiz@pruebas.com', 'password' => Hash::make('12345678'), 'name' => 'Oscar Ruiz', 'role_code' => 'USER', 'position_code' => 'P2', 'area_code' => 'SYS'],
        ]);
    }
}
