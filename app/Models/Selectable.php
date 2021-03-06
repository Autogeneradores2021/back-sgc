<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Parent_;

class Selectable extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public static $rules = [
        "code" => "required|max:10",
        "description" => "required|max:80",
        "enabled" => "required",
    ];

    public function __construct(array $attributes = [], $table){
        parent::__construct($attributes);
        $this->table = $table;
    }
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = '';

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
        'code', 'description', 'own_system', 'enabled'
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
        'description' => 'string', 'code' => 'string', 'enabled' => 'bool', 
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

    public static function createIfNotExist($table, $code, $description) {
        if (strlen($code) > 50) {
            $code = substr($code, 50);
        }
        $count = DB::table($table)->where('code', $code)->count();
        if ($count == 0) {
            $model = new Selectable([
                'code' => $code,
                'description' => $description
            ], $table);
            $model->save();
        }
        return $code;
    }

    // Scopes...

    // Functions ...

    // Relations ...
}
