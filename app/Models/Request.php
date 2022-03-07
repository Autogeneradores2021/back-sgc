<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
class Request extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public static $rules = [
        "request_type_code"=>"required|exists:request_types,code",
        "init_date" => "required|date",
        "init_date" => "required|date",
        "detected_date" => "required|date", 
        "detected_in_code" => "required|exists:detected_places,code",
        "detected_for_id" => "required|exists:users,id",
        "unfulfilled_requirement_code" => "required|exists:unfulfilled_requirements,code",
        "process_lead_id" => "required|exists:users,id",
        "affected_process_code" => "required|exists:affected_processes,code",
        "how_detected_code" => "required|exists:detection_types,code",
        "action_type_code" => "required|exists:action_types,code",
        "evidence_description" => "required",
        "request_description" => "required",
        "evidence_file" => "required",
        "status_code"=> "required|max:10"
    ];


    protected $appends = ['process_lead_name', 'detected_for_name', 'stack'];

    public function getStackAttribute() {
        if ($this->parent_id) {
            return Request::query()->where('id', '=', $this->parent_id)->count() + 1;
        }
        return 0;
    }
    
    public function getProcessLeadNameAttribute() {
        if ($this->process_lead_id) {
            return User::query()->where('id', '=', $this->process_lead_id)->get('name')->first()->name;
        }
        return null;
    }

    public function getDetectedForNameAttribute() {
        if ($this->detected_for_id) {
            return User::query()->where('id', '=', $this->detected_for_id)->get('name')->first()->name;
        }
        return null;
    }

    public static function countByUserAndStatus($type, $user_id, $status) {
        return Request::query()->where('request_type_code', '=', $type)->where('process_lead_id', '=', $user_id)->where('status_code', '=', $status)->count();
    }

    public static function countByPeriod($type, $days = 7) {
        return DB::select(
        'SELECT count(r.DETECTED_DATE) as "count", r.DETECTED_DATE  as "date" '.
        'FROM REQUESTS r '.
        'WHERE r.DETECTED_DATE >= sysdate - ? AND r.REQUEST_TYPE_CODE = ? '.
        'GROUP BY r.DETECTED_DATE', [$days, $type]);
    }

    public static function countByTypeAndStatus($type, $status) {
        if ($status) {
            return Request::query()->where('request_type_code', '=', $type)->whereIn('status_code', $status)->count();
        } else {
            return Request::query()->where('request_type_code', '=', $type)->count();
        }
    }

    public static function updateStatus($id, $status) {
        if ($status == 'TO_FIX') {
            Request::createChild($id);
        }
        return Request::query()->where('id', '=', $id)->update(['status_code' => $status]);
    }

    public static function createChild($id) {
        $old_request = Request::query()->where('id', '=', $id)->get()->first();
        return Request::create([
            "request_type_code" => $old_request->request_type_code,
            "init_date" => $old_request->init_date,
            "detected_date" => $old_request->detected_date, 
            "detected_in_code" => $old_request->detected_in_code,
            "detected_for_id" => $old_request->detected_for_id,
            "unfulfilled_requirement_code" => $old_request->unfulfilled_requirement_code,
            "process_lead_id" => $old_request->process_lead_id,
            "affected_process_code" => $old_request->affected_process_code,
            "how_detected_code" => $old_request->how_detected_code,
            "action_type_code" => $old_request->action_type_code,
            "evidence_description" => $old_request->evidence_description,
            "request_description" => $old_request->request_description,
            "evidence_file" => $old_request->evidence_file,
            "status_code"=> 'PENDING',
            "parent_id" => $old_request->id,
            "request_code" => $old_request->request_code,
        ]);
    }

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
        'request_type_code',
        'init_date',
        'detected_date',
        'detected_in_code',
        'detected_for_id',
        'unfulfilled_requirement_code',
        'process_lead_id',
        'affected_process_code',
        'how_detected_code',
        'action_type_code',
        'request_code',
        'evidence_description',
        'request_description',
        'evidence_file',
        'status_code',
        'created_at',
        'parent_id',
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
        'request_type' => 'string', 'init_date' => 'datetime', 'detected_date' => 'datetime', 'detected_in' => 'string', 'unfulfilled_requirement' => 'string', 'process_affected' => 'string', 'how_detected' => 'string', 'action_type' => 'string', 'request_code' => 'string', 'evidence_description' => 'string', 'request_description' => 'string', 'evidence_file' => 'string', 'status' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
    public $timestamps = true;

    // Scopes...

    // Functions ...

    // Relations ...
}
