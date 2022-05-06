<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

/**
 * @property string $area
 * @property string $position
 * @property int    $created_at
 * @property int    $updated_at
 */
class Filter extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public function rules() {
        return [
            "type" => [
                "required",
                "exists:filter_types,code",
                Rule::unique('filters', 'type')->using(function ($q) { $q->where('value', $this->value ); })
            ],
            "value" => [
                "required",
                "exists:filter_values,code",
                Rule::unique('filters', 'value')->using(function ($q) { $q->where('type', $this->value ); })
            ]
        ];
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'filters';

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
        'id', 'value', 'type', 'created_at', 'updated_at'
    ];

    protected $appends = ['value_name', 'value_description', 'query'];

    public function getValueNameAttribute() {
        return FilterValue::query()->where('code', $this->value)->first()->name;
    }

    public function getQueryAttribute() {
        return FilterValue::query()->where('code', $this->value)->first()->query;
    }

    public function getValueDescriptionAttribute() {
        return FilterValue::query()->where('code', $this->value)->first()->description;
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
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
