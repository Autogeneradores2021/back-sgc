<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $cause
 * @property string $root
 * @property string $analysis_result
 * @property int    $created_at
 * @property int    $updated_at
 */
class Analysis extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public static $rules = [
        "tracking_id" => "required|exists:trackings,id",
        "root" => "required|nullable|max:100",
        "cause" => "required|max:255",
        "analysis_result" => "required"
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'analysis';

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
        'tracking_id', 'cause_1', 'cause_2', 'cause_3', 'cause_4', 'cause_5', 'root', 'analysis_result', 'created_at', 'updated_at'
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
        'cause_1' => 'string', 'cause_2' => 'string', 'cause_3' => 'string', 'cause_4' => 'string', 'cause_5' => 'string', 'root' => 'string', 'analysis_result' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
