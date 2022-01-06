<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property DateTime $tracking_date
 * @property DateTime $tracking_date_period_init
 * @property DateTime $tracking_date_period_end
 * @property string   $result
 * @property string   $result_analysis
 * @property string   $descriptions
 * @property string   $objective
 * @property string   $agree
 * @property int      $total_review
 * @property int      $total_agree
 * @property int      $total_disagre
 * @property int      $percentage
 * @property int      $created_at
 * @property int      $updated_at
 */
class FinishRequest extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public $rules = [
        "request_id" => "required",
        "tracking_id" => "required|exists:trackings,id",
        "user_tracking_id" => "required|exists:users,id",
        "tracking_date" => "required|date",
        "tracking_date_period_init" => "required|date",
        "tracking_date_period_end" => "required|date",
        "result" => "required|nullable|max:30",
        "result_analysis" => "required|nullable|max:150",
        "user_granted_id" => "required|nullable|exists:users,id",
        "descriptions" => "required",
        "objective" => "required|nullable"
        "total_review" => "required|nullable",
        "total_agree" => "required|nullable",
        "total_disagre" => "required|nullable",
        "percentage" => "required|nullable",
        "agree" => "required|nullable|max:50"

    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'finish_requests';

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
        'request_id', 'user_tracking_id', 'tracking_date', 'tracking_date_period_init', 'tracking_date_period_end', 'result', 'result_analysis', 'user_granted_id', 'descriptions', 'objective', 'total_review', 'total_agree', 'total_disagre', 'percentage', 'agree', 'created_at', 'updated_at'
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
        'tracking_date' => 'datetime', 'tracking_date_period_init' => 'datetime', 'tracking_date_period_end' => 'datetime', 'result' => 'string', 'result_analysis' => 'string', 'descriptions' => 'string', 'objective' => 'string', 'total_review' => 'int', 'total_agree' => 'int', 'total_disagre' => 'int', 'percentage' => 'int', 'agree' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'tracking_date', 'tracking_date_period_init', 'tracking_date_period_end', 'created_at', 'updated_at'
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
