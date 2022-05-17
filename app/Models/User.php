<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'username',
        'password',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function deposits() {
        return $this->hasMany(Deposit::class, 'dep_user_id');
    }

    public function purchases() {
        return $this->hasMany(Purchase::class, 'pur_user_id');
    }

    public function balance() {
        return $this->hasOne(Balance::class, 'bal_user_id');
    }
}
