<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_code',
        'position_code',
        'area_code',
        'identification_type',
        'identification_number',
        'phone_number',
        'state_code',
        'actions'
    ];

    /**
     * validation
     * 
     * @var string
     */
    public static $rules = [
        "email" => "required|unique:users,email",
        "name" => "required",
        "identification_type" => "required|exists:identification_types,code",
        "identification_number" => "required"
    ];


    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        // 'created_at',
        'updated_at',
        'email_verified_at',
        'actions'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $appends = ['area_description', 'position_description', 'permissions'];

    public function getPermissionsAttribute($_) {
        if ($this->actions) {
            return json_decode($this->actions);
        }
    }

    public function getAreaDescriptionAttribute($_) {
        if ($this->area_code) {
            return DB::table('areas')->where('code', '=', $this->area_code)->first(['description'])->description;
        }
        return null;
    }

    public function getPositionDescriptionAttribute($_) {
        if ($this->position_code) {
            return DB::table('positions')->where('code', '=', $this->position_code)->first(['description'])->description;
        }
        return null;
    }
    
    public static function getEmailById($id){
        return User::query()->where('id', $id)->first()->email;
    }



    public static function generateRandomPassword($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
