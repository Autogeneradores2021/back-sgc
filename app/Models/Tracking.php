<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public static function updateStep($id, $step) {
        $record = Tracking::query()->where('id', '=', $id)->get()->first();
        $record->last_step_complete = $step;
        $record->save();
        return $record;
    }

    public static function updateStatus($id, $status) {
        $record = Tracking::query()->where('id', '=', $id)->get()->first();
        $record->status_code = $status;
        $record->save();
        return $record;
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
        'upgrade_plan_id', 'follow_process_description', 'percentage', 'goal_description', 'created_at', 'updated_at'
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
        'follow_process_description' => 'string', 'goal_description' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
