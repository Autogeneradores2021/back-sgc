<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property string $title
 * @property string $description
 * @property string $icon
 * @property int    $created_at
 * @property int    $updated_at
 */
class Issue extends Model
{
 
    /**
     * validation
     * 
     * @var string
     */
    public static $rules = [
        "request_id" => "required|exists:requests,id",
        "title" => "required|max:100",
        "icon_code" => "required|max:50"
    ];

    protected $appends = ['icon_name'];

    public function getIconNameAttribute() {
        if ($this->icon_code) {
            return DB::table('icons')->where('code', $this->icon_code)->first()->description;
        }
        return null;
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'issues';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id', 'title', 'description', 'icon_code', 'created_at', 'updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string', 'description' => 'string', 'icon_code' => 'string'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    public static function createRequest($user, $request) {
        Issue::create([
            'request_id' => $request->id,
            'title' => '<strong>Se ha creado una solicitud nueva</strong>',
            'description' => '<p><strong> '.$user->name.' </strong> ha creado un nueva solicitud con codigo <strong> '.$request->request_code.' </strong> a '.$request->process_lead_name.' por <strong> '.$request->action_type_name.' </strong></p>',
            'icon_code' => 'NEW'
        ]);
    }

    public static function createWorkTeam($user, $request, $workTeam) {
        $team_description = '<div>';
        foreach ($workTeam as $value) {
            $team_description .= '<div><strong> '.$value['name'].' </strong></div>';
        }
        $team_description .= '</div>';
        Issue::create([
            'request_id' => $request->id,
            'title' => '<strong>Se ha agregado un equipo de trabajo</strong>',
            'description' => '<p><strong> '.$user->name.' </strong> ha creado grupo de trabajo a la solicitud <strong> '.$request->request_code.' </strong> con los siguientes integrantes: </p>'.$team_description,
            'icon_code' => 'WTEAM'
        ]);
    }

    public static function createUPlan($user, $request, $uplan, $type) {
        $uplan_description = '<div>';
        foreach ($uplan as $value) {
            $uplan_description .= '<div><strong> '.$value['goal_description'].' </strong></div>';
        }
        $uplan_description .= '</div>';
        Issue::create([
            'request_id' => $request->id,
            'title' => '<strong>Se ha agregado algunas '.$type.'</strong>',
            'description' => '<p><strong> '.$user->name.' </strong> ha agregado algunas '.$type.' a la solicitud <strong> '.$request->request_code.' </strong> con los siguientes descripciones: </p>'.$uplan_description,
            'icon_code' => 'UPLAN'
        ]);
    }

    public static function createAnalysis($user, $request, $analysis) {
        $analysis_description = '<div>';
        foreach ($analysis as $value) {
            $analysis_description .= '<div> <strong> Pregunta: </strong>'.$value['question'].' <strong> Respuesta: </strong> '.$value['answer'].' </div>';
        }
        $analysis_description .= '</div>';
        Issue::create([
            'request_id' => $request->id,
            'title' => '<strong>Se ha solucionado el analisis de causas</strong>',
            'description' => '<p><strong> '.$user->name.' </strong> ha solucionado el cuestionario de analiss de causas a la solicitud <strong> '.$request->request_code.' </strong> de la siguiente forma: </p>'.$analysis_description,
            'icon_code' => 'ANALYSIS'
        ]);
    }

    public static function createTracking($user, $request, $tracking) {
        Issue::create([
            'request_id' => $request->id,
            'title' => '<strong>Se a actualizado el Seguimiento</strong>',
            'description' => '<p><strong> '.$user->name.' </strong> ha actualizado el seguimiento al  <strong> '.$tracking['percentage'].'% </strong> por <strong> '.$tracking['goal_description'].' </strong><p>',
            'icon_code' => 'TRACKING'
        ]);
    }

    public static function createFinishRequest($user, $request, $finish_request) {
        Issue::create([
            'request_id' => $request->id,
            'title' => '<strong>Se ha finalizado la solicitud</strong>',
            'description' => '<p><strong> '.$user->name.' </strong> ha finalizado la solicitud solicitud con codigo <strong> '.$request->request_code.' </strong> con un resultado <strong>'.$finish_request->result_description.'</strong></p>',
            'icon_code' => 'FREQUEST'
        ]);
    }
}