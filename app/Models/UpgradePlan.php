<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string   $upgrade_plan_type
 * @property string   $title
 * @property string   $person_assigned
 * @property string   $unit_measurement
 * @property string   $goal_description
 * @property string   $follow_process_description
 * @property string   $evidence_file
 * @property string   $status
 * @property DateTime $init_date
 * @property DateTime $end_date
 * @property DateTime $finish_date
 * @property int      $percentage
 * @property int      $created_at
 * @property int      $updated_at
 */
class UpgradePlan extends Model
{

    /**
     * validation
     *
     * @var string 
     */
    public $rules = [
        "tracking_id" => "required|exists:trackings,id",
        "upgrade_plan_type" => "required",
        "title" => "required|max:255",
        "person_assigned" => "required|max:100",
        "init_date" => "required|date",
        "end_date" => "required|date",
        "unit_measurement" => "required|nullable",
        "goal_description" => "required|nullable",
        "follow_process_description" => "required",
        "finish_date" => "required|date",
        "evidence_file" => "required|nullable",
        "percentage" => "required|nullable",
        "status" => "required|max:10"

    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'upgrade_plans';

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
        'upgrade_plan_type', 'request_id', 'title', 'person_assigned', 'init_date', 'end_date', 'unit_measurement', 'goal_description', 'follow_process_description', 'finish_date', 'evidence_file', 'percentage', 'status', 'created_at', 'updated_at'
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
        'upgrade_plan_type' => 'string', 'title' => 'string', 'person_assigned' => 'string', 'init_date' => 'datetime', 'end_date' => 'datetime', 'unit_measurement' => 'string', 'goal_description' => 'string', 'follow_process_description' => 'string', 'finish_date' => 'datetime', 'evidence_file' => 'string', 'percentage' => 'int', 'status' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'init_date', 'end_date', 'finish_date', 'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    // Scopes...

    // Functions ...

    // Relations ...
}
