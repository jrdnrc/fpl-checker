<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'token',
        'refresh_token',
        'avatar',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * @param \Laravel\Socialite\Two\User $user
     * @return User
     */
    public static function fromGoogleUser(\Laravel\Socialite\Two\User $user) : self
    {
        return static::create([
            'name'          =>  $user->name,
            'email'         =>  $user->email,
            'token'         =>  $user->token,
            'refresh_token' =>  $user->refreshToken,
            'avatar'        =>  $user->avatar,
            'google_id'     =>  $user->id,
        ]);
    }
}
