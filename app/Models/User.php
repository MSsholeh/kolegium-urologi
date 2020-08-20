<?php

namespace App\Models;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\PasswordBroker;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'name', 'email', 'password', 'nik', 'nim', 'university_id', 'year', 'semester'
//    ];

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    public function sendPasswordResetNotification($token)
//    {
//        $this->notify(new ResetPasswordNotification($token));
//    }

    public function sendPasswordResetNotification($token) {
        $notification = new ResetPassword($token);
        $notification->createUrlUsing(function ($user) {
            $token = app('auth.password.broker')->createToken($user);
            return route('web.password.reset', $token);
        });
        $this->notify($notification);
    }

    public function registrants(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Registrant::class);
    }

    public function registrants_graduation(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RegistrantGraduation::class);
    }

    public function registrants_certificate(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RegistrantCertificate::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function countSubmission(): int
    {
        return $this->registrants()->count();
    }

}
