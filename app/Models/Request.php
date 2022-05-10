<?php

namespace App\Models;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        "evidence_file_0" => "required",
        "status_code"=> "required|max:10"
    ];


    protected $appends = ['process_lead_name', 'detected_for_name', 'stack', 'affected_process_name', 'action_type_name', 'unfulfilled_requirement_name', 'detected_in_name', 'how_detected_name', 'owner'];

    public function getStackAttribute() {
        if ($this->parent_id) {
            return Request::query()->where('id', '=', $this->parent_id)->count() + 1;
        }
        return 0;
    }
    
    public function getOwnerAttribute() {
        try {
            $user_id = auth()->user()->id;
        } catch (\Throwable $th) {
            return  0;
        }
        return Request::ifGrandAccess(
            $this->request_type_code,
            $user_id,
            $this->status_code,
            $this->id,
        );
    }

    public function getDetectedInNameAttribute() {
        if ($this->detected_in_code) {
            return DB::table('detected_places')->where('code', $this->detected_in_code)->first()->description   ;
        }
        return null;
    }

    public function getHowDetectedNameAttribute() {
        if ($this->how_detected_code) {
            return DB::table('detection_types')->where('code', $this->how_detected_code)->first()->description   ;
        }
        return null;
    }

    public function getUnfulfilledRequirementNameAttribute() {
        if ($this->unfulfilled_requirement_code) {
            return DB::table('unfulfilled_requirements')->where('code', $this->unfulfilled_requirement_code)->first()->description   ;
        }
        return null;
    }

    public function getAffectedProcessNameAttribute() {
        if ($this->affected_process_code) {
            return DB::table('affected_processes')->where('code', $this->affected_process_code)->first()->description   ;
        }
        return null;
    }

    public function getActionTypeNameAttribute() {
        if ($this->action_type_code) {
            return DB::table('action_types')->where('code', $this->action_type_code)->first()->description   ;
        }
        return null;
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
        $query = DB::select(<<<SQL
            SELECT COUNT(r.ID) AS count  FROM REQUESTS r 
            LEFT JOIN TEAM_MEMBERS tm 
            ON tm.REQUEST_ID = r.ID
            WHERE
            r.STATUS_CODE = :status AND
            r.REQUEST_TYPE_CODE = :type AND
            ((r.PROCESS_LEAD_ID = :user_id AND tm.IS_LEAD = :is_lead) OR (tm.USER_ID = :user_id AND tm.IS_LEAD = :is_lead))
        SQL,[
            'status' => $status,
            'type' => $type,
            'user_id' => $user_id,
            'is_lead' => 0,
        ]);
        return $query[0]->count;
    }

    public static function ifGrandAccess($type, $user_id, $status, $request_id) {
        $query = DB::select(<<<SQL
            SELECT COUNT(r.ID) AS count FROM REQUESTS r 
            LEFT JOIN TEAM_MEMBERS tm 
            ON tm.REQUEST_ID = r.ID
            WHERE
            r.id = :request_id AND
            r.STATUS_CODE = :status AND
            r.REQUEST_TYPE_CODE = :type AND
            (tm.USER_ID = :user_id or r.PROCESS_LEAD_ID = :user_id)
        SQL, [
            'status' => $status,
            'type' => $type,
            'user_id' => $user_id,
            'request_id' => $request_id,
        ]);
        return $query[0]->count >= 1;
    }

    public static function countByPeriod($type, $days_back = 6) {
        return DB::select(<<<SQL
        SELECT dates,(
            SELECT count(1)
            FROM requests rq
            WHERE rq.request_type_code = :type_code AND TRUNC(rq.CREATED_AT) = dates 
        ) as total
        FROM (
            SELECT TRUNC(SYSDATE-:days_back)+rownum-1 AS dates
            FROM DUAL
            CONNECT BY LEVEL <= (SYSDATE - (SYSDATE-:days_back))+1
        )
        ORDER BY dates DESC
        SQL, [
            'days_back' => $days_back,
            'type_code' => $type,
        ]);
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
            "position_code" => $old_request->position_code,
            "area_code" => $old_request->area_code,
        ]);
    }

    public static function local($value) {
        $dt = new DateTime($value, new DateTimeZone('America/New_York'));

        return $dt->format('d-m-Y');
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

    public static function toNotification($id) {
        $request = Request::query()->where('id', $id)->first();
        $to = [
            User::getEmailById($request->process_lead_id),
            User::getEmailById($request->detected_for_id),
        ];
        foreach (TeamMember::query()->where('request_id', $id)->get() as $member) {
            array_push($to, User::getEmailById($member->user_id));
        }
        foreach (UpgradePlan::query()->where('request_id', $id)->get() as $uplan) {
            array_push($to, User::getEmailById($uplan->person_assigned_id));
        }
        $finish_request = FinishRequest::query()->where('request_id', $id)->first();
        if ($finish_request) {
            User::getEmailById($finish_request->user_tracking_id);
            User::getEmailById($finish_request->user_granted_id);
        }
        return $to;
    }

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
        'position_code',
        'area_code',
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
        'request_type' => 'string', 'init_date' => 'datetime', 'detected_date' => 'datetime', 'detected_in' => 'string', 'unfulfilled_requirement' => 'string', 'process_affected' => 'string', 'how_detected' => 'string', 'action_type' => 'string', 'request_code' => 'string', 'evidence_description' => 'string', 'request_description' => 'string', 'evidence_file' => 'string', 'status' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'
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
