<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $area
 * @property string $position
 * @property int    $created_at
 * @property int    $updated_at
 */
class FilterType extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public static $rules = [
        "code" => "required|unique:filters,code",
        "name" => "required",
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'filter_types';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'code';

    public $incrementing = false;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'created_at', 'updated_at', 'enabled'
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
