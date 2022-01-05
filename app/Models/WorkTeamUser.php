<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $area
 * @property string $position
 * @property int    $created_at
 * @property int    $updated_at
 */
class WorkTeamUser extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public $rules = [
        "name" => "required"
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'work_team_users';

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
        'user_id', 'area', 'position', 'created_at', 'updated_at'
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
        'area' => 'string', 'position' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
