<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\FilterType;
use App\Models\FilterValue;
use App\Models\Role;
use App\Models\SecurityUser;
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
            array(
                "CODE" => "BOTE",
                "DESCRIPTION" => "El Bote",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "SAIRE",
                "DESCRIPTION" => "El Saire",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "ZNORTE",
                "DESCRIPTION" => "Zona Norte",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "ZSUR",
                "DESCRIPTION" => "Zona Sur",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "ZOCCIDENTE",
                "DESCRIPTION" => "Zona Occidente",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "ZCENTRO",
                "DESCRIPTION" => "Zona Centro",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            )
        ],
        'unfulfilled_requirements' => [
            array(
                "CODE" => "ISO",
                "DESCRIPTION" => "ISO 270001",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "AMB",
                "DESCRIPTION" => "Ambiental",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "NISO14001",
                "DESCRIPTION" => "Norma ISO 14001",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "NISO45001",
                "DESCRIPTION" => "Norma ISO 55001",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "NISO9001",
                "DESCRIPTION" => "Norma ISO 9001",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "INTERNO",
                "DESCRIPTION" => "Interno",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "NISO55001",
                "DESCRIPTION" => "Norma ISO 55001",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "LEGAL",
                "DESCRIPTION" => "Legal",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            )
        ],
        'affected_processes' => [
            array(
                "CODE" => "MANTINFR",
                "DESCRIPTION" => "Expansión de infraestructura",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "GESFIN",
                "DESCRIPTION" => "Gestión financiera",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "PLAESTR",
                "DESCRIPTION" => "Planeación estratégica",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "GESTPROY",
                "DESCRIPTION" => "Gestión proyectos",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "RSYA",
                "DESCRIPTION" => "Responsabilidad social y ambiental",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "SERV",
                "DESCRIPTION" => "Servicio al cliente",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "GESTADQ",
                "DESCRIPTION" => "Gestión adquisiciones",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "GESJUR",
                "DESCRIPTION" => "Gestión jurídica",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "GESTFAC",
                "DESCRIPTION" => "Gestión facilidades",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "SGC",
                "DESCRIPTION" => "Sistema de gestion de calidad",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "FAC",
                "DESCRIPTION" => "Facturación de energía",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "PER",
                "DESCRIPTION" => "Control Pérdidas",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "DIS",
                "DESCRIPTION" => "Operación infraestructura",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "SUS",
                "DESCRIPTION" => "Control cartera",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "CONTINT",
                "DESCRIPTION" => "Control interno",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "MANTINFSUB",
                "DESCRIPTION" => "Mantenimiento infraestructura subestaciones",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "GESTEC",
                "DESCRIPTION" => "Gestión tecnológica",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            )
        ],
        'detection_types' => [
            array(
                "CODE" => "AUDINT",
                "DESCRIPTION" => "Auditoría interna de calidad",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "AUDINTSST",
                "DESCRIPTION" => "Auditoría interna seguridad y salud en el trabajo",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "CLIEXT",
                "DESCRIPTION" => "Cliente externo",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "RIESGOS",
                "DESCRIPTION" => "Riesgos",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "REVDIR",
                "DESCRIPTION" => "Revisión por la dirección",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "PROCESOS",
                "DESCRIPTION" => "Procesos",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "CLIEINT",
                "DESCRIPTION" => "Cliente interno",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "AUDINTAMB",
                "DESCRIPTION" => "Auditoría interna ambiental",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "AUDINTACT",
                "DESCRIPTION" => "Auditoría interna gestión de activos",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "AUDINTCERT",
                "DESCRIPTION" => "Auditoría ente certificador",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            )
        ],
        'action_types' => [
            array(
                "CODE" => "SUGGEST",
                "DESCRIPTION" => "Mejora",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "PREVENTIVA",
                "DESCRIPTION" => "Preventiva",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            ),
            array(
                "CODE" => "CORRECTIVA",
                "DESCRIPTION" => "Correctiva",
                "OWN_SYSTEM" => "0",
                "ENABLED" => "1"
            )
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
            [ 'code' => 'TO_FIX', 'description' => 'Requiere substanción' ],
        ],
        # users
        'areas' => [
            [ 'code' => 'EXTERNO', 'description' => 'Area externa' ],
        ],
        'positions' => [
            [ 'code' => 'EXTERNO', 'description' => 'Area externa' ],
            [ 'code' => 'EN_MISION', 'description' => 'En misión' ],
        ],
        'identification_types' => [
            [ 'code' => 'CC', 'description' => 'Cedula de ciudadanía' ],
            [ 'code' => 'CE', 'description' => 'Cedula de extranjería' ],
            [ 'code' => 'NIT', 'description' => 'NIT' ],
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
            [ 'code' => 'EXTERNO', 'name' => 'Usuario externo'],
            [ 'code' => 'BOLSA', 'name' => 'Usuario de bolsa'],
            [ 'code' => 'NOMINA', 'name' => 'Usuario de nomina'],
        ]);
        print("Roles  OK\r\n");
        FilterType::insert([
            [
                "CODE" => "LP",
                "NAME" => "Líder del proceso",
                "DESCRIPTION" => "Formulario de creación de solicitudes",
            ],
            [
                "CODE" => "DP",
                "NAME" => "Detectado por",
                "DESCRIPTION" => "Formulario de creación de solicitudes",
            ],
            [
                "CODE" => "MET",
                "NAME" => "Miembro de equipo de trabajo",
                "DESCRIPTION" => "Formulario de atención a la solicitud paso 1",
            ],
            [
                "CODE" => "LET",
                "NAME" => "Lider de equipo de trabajo",
                "DESCRIPTION" => "Formulario de atención a la solicitud paso 1",
            ],
            [
                "CODE" => "ACI",
                "NAME" => "Responsable de la acción de corrección inmediata",
                "DESCRIPTION" => "Formulario de atención a la solicitud Paso 2",
            ],
            [
                "CODE" => "AC",
                "NAME" => "Responsable de la acción correctiva",
                "DESCRIPTION" => "Formulario de atención a la solicitud Paso 4",
            ],
            [
                "CODE" => "QHES",
                "NAME" => "Quien hace el seguimiento",
                "DESCRIPTION" => "Formulario de evaluación a la solicitud",
            ],
            [
                "CODE" => "QHLE",
                "NAME" => "Quien hace la evaluación",
                "DESCRIPTION" => "Formulario de evaluación a la solicitud",
            ]
        ]);
        print("Tipos de filtro  OK\r\n");
        foreach (FilterType::query()->get() as $type) {
            FilterValue::insert([
                array(
                    "CODE" => $type->code."_BOLSA",
                    "NAME" => "En misión",
                    "DESCRIPTION" => "Funcionarios de bolsa ",
                    "QUERY" => "role_code = 'BOLSA'"
                ),
                array(
                    "CODE" => $type->code."_NOMINA",
                    "NAME" => "Nomina",
                    "DESCRIPTION" => "Funcionarios de nomina",
                    "QUERY" => "role_code = 'NOMINA'"
                ),
                array(
                    "CODE" => $type->code."_EXTERNO",
                    "NAME" => "Entidades externas",
                    "DESCRIPTION" => "Entidades interventoras o entidades externas",
                    "QUERY" => "role_code = 'EXTERNO'"
                ),
                array(
                    "CODE" => $type->code."_JEFE",
                    "NAME" => "Jefes",
                    "DESCRIPTION" => "Jefes de oficina o division",
                    "QUERY" => "position like '%JEFE%'"
                ),
                array(
                    "CODE" => $type->code."_PROFESIONALES",
                    "NAME" => "Profesionales",
                    "DESCRIPTION" => "Profesionales I, II o II",
                    "QUERY" => "position like '%PROFESIONAL%'"
                ),
                array(
                    "CODE" => $type->code."_AUXILIARES",
                    "NAME" => "Auxiliares",
                    "DESCRIPTION" => "Auxiliares ",
                    "QUERY" => "position like '%AUXILIAR%'"
                ),
                array(
                    "CODE" => $type->code."_TECNOLOGO",
                    "NAME" => "Tecnologos",
                    "DESCRIPTION" => "Tecnologos",
                    "QUERY" => "position like '%TECNOLOGO%'"
                ),
                array(
                    "CODE" => $type->code."_PRACTICANTE",
                    "NAME" => "Practicantes",
                    "DESCRIPTION" => "Practicantes universitarios",
                    "QUERY" => "position like '%PRACTICANTE%'"
                ),
                array(
                    "CODE" => $type->code."_APRENDIZ",
                    "NAME" => "Aprendiz",
                    "DESCRIPTION" => "Aprendiz SENA",
                    "QUERY" => "position like '%APRENDIZ%'"
                ),
                array(
                    "CODE" => $type->code."_ASESOR",
                    "NAME" => "Asesor",
                    "DESCRIPTION" => "Asesor",
                    "QUERY" => "position like '%ASESOR%'"
                )
            ]);
        }
        print("Valores de filtros  OK\r\n");
        User::insert([
            ['email' => 'admin@admin.com', 'password' => Hash::make('12345679'), 'name' => 'ADMINISTRADOR', 'role_code' => 'ADMIN', 'position_code' => 'EXTERNO', 'area_code' => 'EXTERNO'],
        ]);
        print("Usuarios de prueba  OK\r\n");
        Employee::getUsers();
        print("Usuarios de nomina  OK\r\n");
        SecurityUser::getAllUsers();
        print("Usuarios de bolsa  OK\r\n");
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
