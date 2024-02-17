<?php

namespace App\Models;

use App\Http\Controllers\RrhhController;
use DateTime;
use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {

    protected $hidden = ['password'];

    protected $fillable = [
        'mail',
        'password'
    ];

    use HasApiTokens, HasFactory, Notifiable;
   
    public function incs(): HasMany{
        return $this->hasMany(Inc::class);
    }

    public function rrhhs(): HasMany{
        return $this->hasMany((Rrhh::class));
    }

    public function trabajo(): BelongsTo{
        return $this->belongsTo(Trabajo::class);
    }
    public function role(): HasOneThrough{
        return $this->hasOneThrough(Trabajo::class, Role::class);
    }



};

        