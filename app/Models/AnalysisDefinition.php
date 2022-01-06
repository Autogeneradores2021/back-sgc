<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $problem_understand
 * @property string $local_revision
 * @property string $viability_test
 * @property int    $created_at
 * @property int    $updated_at
 */
class AnalysisDefinition extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public $rules = [
        "tracking_id" => "required|exists:trackings,id",
        "problem_understand" => "required",
        "local_revision" => "required",
        "viability_test" => "required"
    ];


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'analysis_definitions';

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
        'request_id', 'problem_understand', 'local_revision', 'viability_test', 'created_at', 'updated_at'
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
        'problem_understand' => 'string', 'local_revision' => 'string', 'viability_test' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
