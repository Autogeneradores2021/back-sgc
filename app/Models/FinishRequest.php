<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    public static $rules = [
        "request_id" => "required|exists:requests,id",
        "user_tracking_id" => "required|exists:users,id",
        "tracking_date" => "required|date",
        "tracking_date_period_init" => "required|date",
        "tracking_date_period_end" => "required|date",
        "result_code" => "required|exists:result_types,code",
        "result_analysis" => "required|max:150",
        "total_review" => "required",
        "total_agree" => "required",
        "total_disagree" => "required",
        "total_fulfilment" => "required"
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
        
        "request_id",
        "user_tracking_id",
        "tracking_date",
        "tracking_date_period_init",
        "tracking_date_period_end",
        "result_code",
        "result_analysis",
        "total_review",
        "total_agree",
        "total_disagree",
        "total_fulfilment",
        'created_at',
        'updated_at'
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
        'tracking_date' => 'datetime', 'tracking_date_period_init' => 'datetime', 'tracking_date_period_end' => 'datetime', 'result' => 'string', 'result_analysis' => 'string', 'descriptions' => 'string', 'objective' => 'string', 'total_review' => 'int', 'total_agree' => 'int', 'total_disagre' => 'int', 'percentage' => 'int', 'total_fulfilment' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
    public $timestamps = true;

    protected $appends = ['user_tracking_name', 'result_description'];

    public function getUserTrackingNameAttribute($_) {
        if ($this->user_tracking_id) {
            return User::query()->where('id', '=', $this->user_tracking_id)->first(['name'])->name;
        }
        return null;
    }

    public function getResultDescriptionAttribute($_) {
        if ($this->result_code) {
            return DB::table('result_types')->where('code', '=', $this->result_code)->first(['description'])->description;
        }
        return null;
    }

    // Scopes...

    // Functions ...

    // Relations ...
}
