<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $area
 * @property string $position
 * @property int    $created_at
 * @property int    $updated_at
 */
class TeamMember extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public static $rules = [
        "request_id" => "required|exists:requests,id",
        "user_id" => "exists:users,id",
        "area" => "required",
        "position" => "required",
        "name" => "required"
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'team_members';

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
        'user_id', 'area', 'position', 'created_at', 'updated_at', 'name', "request_id", 'is_lead'
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
        'is_lead' => 'boolean', 'name' => 'string', 'area' => 'string', 'position' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
    public $timestamps = true;

    // Scopes...

    // Functions ...

    // Relations ...
}
