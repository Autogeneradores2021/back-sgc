<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Selectable;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public $selectables = [
        'request_types' => [
            [ 'code' => 'SGC', 'description' => 'Sistema de gestion de calidad' ],
            [ 'code' => 'SCI', 'description' => 'Sistema de control interno' ],
        ],
        # Request selectables
        'status' => [
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
            [ 'code' => 'PRE', 'description' => 'Preventiva' ],
            [ 'code' => 'COR', 'description' => 'Correctiva' ],
            [ 'code' => 'RES', 'description' => 'Restrictiva' ],
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
            [ 'code' => 'SUB', 'description' => 'Subsanado' ],
        ],
        # users
        'areas' => [
            [ 'code' => 'FAC', 'description' => 'Facturacion' ],
            [ 'code' => 'PER', 'description' => 'Perdidas' ],
            [ 'code' => 'DIS', 'description' => 'Distribucion' ],
            [ 'code' => 'SUS', 'description' => 'Suspension' ],
            [ 'code' => 'SYS', 'description' => 'Sistemas' ],
        ],
        'positions' => [
            [ 'code' => 'LEAD', 'description' => 'Lider' ],
            [ 'code' => 'AUD', 'description' => 'Auditor' ],
        ]
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

        User::factory(10)->create();
    }
}
