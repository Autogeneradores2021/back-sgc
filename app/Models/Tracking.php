<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * @property string $title
 * @property string $description
 * @property string $icon
 * @property int    $created_at
 * @property int    $updated_at
 */
class Tracking extends Model
{
 
    /**
     * validation
     * 
     * @var string
     */
    public static $rules = [
        "upgrade_plan_id" => "required|exists:upgrade_plans,id",
        "follow_process_description" => "required",
        "percentage" => "required",
        "goal_description" => "required"
    ];

    public static function verify($id) {
        $request_id = UpgradePlan::query()->where('id', '=', $id)->get('request_id')->first()->request_id;
        $collection = UpgradePlan::query()->where('request_id', '=', $request_id)->where('upgrade_plan_type_code', '=', 'DEF')->get();
        $valid = true;
        Log::info('SE VERIFICA');
        Log::info($collection);
        foreach ($collection as $value) {
            Log::info('VERIFICANDO SEGUIMIENTOS');
            $record = Tracking::query()->where('upgrade_plan_id', '=', $value->id)->orderBy('id', 'desc')->first();
            Log::info($record);
            if (!$record || $record->percentage < 100) {
                $valid = false;
            }
        }
        if ($valid) {
            Log::info('SE CIERRA');
            Request::updateStatus($request_id, 'R_TO_CLOSE');
        }
        return null;
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'trackings';

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
        'upgrade_plan_id', 'follow_process_description', 'percentage', 'goal_description', 'evidence_file', 'created_at', 'updated_at'
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
        'follow_process_description' => 'string', 'goal_description' => 'string'
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

    // Scopes...

    // Functions ...

    // Relations ...
}
