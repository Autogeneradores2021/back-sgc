<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $code
 * @property int    $created_at
 * @property int    $updated_at
 */
class QuestionaryAnswers extends Model
{

    /**
     * validation
     *
     * @var string
     */
    public static $rules = [
        "request_id" => "required|exists:requests,id",
        "question" => "required",
        "answer"=>"required"
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'questionary_answers';

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
        'question', 'answer', 'is_root', 'request_id', 'created_at', 'updated_at'
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
        'question' => 'string', 'answer' => 'string', 'created_at' => 'timestamp', 'updated_at' => 'timestamp'
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
