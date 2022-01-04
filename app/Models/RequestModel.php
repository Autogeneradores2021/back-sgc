<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string   $request_type
 * @property string   $detected_in
 * @property string   $unfulfilled_requirement
 * @property string   $process_affected
 * @property string   $how_detected
 * @property string   $action_type
 * @property string   $request_code
 * @property string   $evidence_description
 * @property string   $request_description
 * @property string   $evidence_file_path
 * @property string   $status
 * @property DateTime $init_date
 * @property DateTime $detected_date
 * @property int      $created_at
 * @property int      $updated_at
 */
class RequestModel extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $rules = [
        "init_date" => "required",
        "init_date" => "required",
        "detected_date" => "required",
        "detected_in" => "required",
        "detected_for_id" => "required",
        "unfulfilled_requirement" => "required",
        "process_lead_id" => "required",
        "process_affected" => "required",
        "how_detected" => "required",
        "action_type" => "required",
        "request_code" => "required",
        "evidence_description" => "required",
        "request_description" => "required",
        "evidence_file_path" => "required",
        "status" => "required",
        "created_at" => "required",
        "updated_at" => "required"
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'requests';

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
        'request_type',
        'init_date', 'detected_date', 'detected_in', 'detected_for_id', 'unfulfilled_requirement', 'process_lead_id', 'process_affected', 'how_detected', 'action_type', 'request_code', 'evidence_description', 'request_description', 'evidence_file_path', 'status', 'created_at', 'updated_at => "required"'
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
        'request_type' => 'string', 'init_date' => 'datetime', 'detected_date' => 'datetime', 'detected_in' => 'string', 'unfulfilled_requirement' => 'string', 'process_affected' => 'string', 'how_detected' => 'string', 'action_type' => 'string', 'request_code' => 'string', 'evidence_description' => 'string', 'request_description' => 'string', 'evidence_file_path' => 'string', 'status' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'init_date', 'detected_date', 'created_at', 'updated_at'
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
