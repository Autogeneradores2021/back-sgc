<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $created_at
 * @property int $updated_at
 */
class RoleForm extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public $rules = [
        "role_id" => "required|exists:roles,id",
        "form_id" => "required|exists:forms,id"
    ]; 

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_forms';

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
        'role_id', 'form_id', 'created_at', 'updated_at'
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
        'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
