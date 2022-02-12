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
        "request_id" => "required|exists:requests,id",
        "step_count" => "required",
        "last_step_complete" => "required",
        "status_code" => "required"
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
        'request_id', 'step_count', 'last_step_complete', 'status_code', 'created_at', 'updated_at'
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
        'title' => 'string', 'description' => 'string', 'icon' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
    public $timestamps = false;

    // Scopes...

    // Functions ...

    // Relations ...
}
