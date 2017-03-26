<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public $casts = [
        'token' => 'json',
    ];

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
            'token'         =>  [
                'access_token'  => $user->token,
                'refresh_token' => $user->refreshToken,
                'expires_in'    => $user->expiresIn,
                'authorization' => 'Bearer',
            ],
            'refresh_token' =>  $user->refreshToken,
            'expires_in'    =>  $user->expiresIn,
            'avatar'        =>  $user->avatar,
            'google_id'     =>  $user->id,
        ]);
    }

    /**
     * @param array $token
     * @return bool
     */
    public function tokenMatches(array $token) : bool
    {
        return $this->token() === $token;
    }

    /**
     * @param array $token
     * @return void
     */
    public function saveNewToken(array $token) : void
    {
        $this->token = $token;
        $this->save();
    }

    /**
     * @return array
     */
    public function token() : array
    {
        return $this->token;
    }
}
