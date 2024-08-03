<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'prenom',
        'cin',
        'cnss',
        'date_de_naissance',
        'genre',
        'salaire',
        'tel',
        'adresse',
        'photo',
        'email',
        'password',
        'role',
        'department_id',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_de_naissance' => 'date',
        'date_embauche' => 'date',
        'salaire' => 'decimal:2',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function conges()
    {
        return $this->hasMany(Conge::class);
    }
}
