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
class TrackingIssue extends Model
{
 
    /**
     * validation
     * 
     * @var string
     */
    public static $rules = [
        "request_id" => "required|exists:requests,id",
        "title" => "required|max:100",
        "description" => "required|nullable",
        "icon" => "required|nullable|max:50"
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'issues';

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
        'request_id', 'title', 'description', 'icon', 'created_at', 'updated_at'
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
