<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $standard_type
 * @property string $description
 * @property string $follow
 * @property int    $percentage
 * @property int    $created_at
 * @property int    $updated_at
 */
class Standard extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public $rules = [
        "standard_type" => "required",
        "description" => "required",
        "user_id" => "required|exists:users,id",
        "follow" => "required",
        "percentage" => "required"

    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'standards';

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
        'standard_type', 'description', 'user_id', 'follow', 'percentage', 'created_at', 'updated_at'
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
        'standard_type' => 'string', 'description' => 'string', 'follow' => 'string', 'percentage' => 'int', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
