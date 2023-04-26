<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'strava_id', 'strava_access_token', 'strava_refresh_token', 'strava_expires_at', 'avatar', 'email',
        'fullname', 'gender', 'is_active', 'last_login', 'department_id', 'token_md5'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'strava_access_token', 'strava_refresh_token', 'strava_expires_at', 'updated_at'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
    }


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
        return [
            //'firstname' => $this->first_name,
            //'lastname' => $this->last_name,
            //'email' => $this->email
        ];
    }
}
