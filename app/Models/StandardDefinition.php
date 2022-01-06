<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property boolean  $indicator
 * @property boolean  $event
 * @property boolean  $time
 * @property string   $what_indicator
 * @property string   $what_event
 * @property string   $description
 * @property DateTime $what_time_init
 * @property DateTime $what_time_end
 * @property int      $created_at
 * @property int      $updated_at
 */
class StandardDefinition extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public $rules = [
        "tracking_id" => "required|exists:trackings,id",
        "indicator" => "required|boolean:false",
        "event" => "required|boolean:false",
        "time" => "required|boolean:false",
        "what_indicator" => "required",
        "what_event" => "required",
        "what_time_init" => "required|date",
        "what_time_end" => "required|date",
        "description" => "description"
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'standard_definitions';

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
        'request_id', 'indicator', 'event', 'time', 'what_indicator', 'what_event', 'what_time_init', 'what_time_end', 'description', 'created_at', 'updated_at'
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
        'indicator' => 'boolean', 'event' => 'boolean', 'time' => 'boolean', 'what_indicator' => 'string', 'what_event' => 'string', 'what_time_init' => 'datetime', 'what_time_end' => 'datetime', 'description' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'what_time_init', 'what_time_end', 'created_at', 'updated_at'
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
