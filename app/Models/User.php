<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\BelongsToRelationship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class User extends Model{

    
    // public ?int $id = null;
    // public string $nombre;
    // public string $apellidos;
    // public string $mail;
    // public string $telefono;
    // public string $sexo;
    // public bool $admin;
    // public bool $superAdmin;
    // public int $venue_id;
    // public bool $departamento;
    // public bool $working;


    //  public string $fAlta;
    //  public string $fBaja;
    // public string $password;
    // public int $role_id;

    // public ?int $piso_id;
    // public ?int $puerta_id;


   
    public function incs(): HasMany{
        return $this->hasMany(Inc::class);
    }

    // public function trabajos(): HasMany{
    //     return $this->hasMany(Trabajo::class);
    // }

    // public function trabajos(): HasMany{
    //     return $this->hasMany(Trabajo::class);
    // }

    public function trabajo(): BelongsTo{
        return $this->belongsTo(Trabajo::class);
    }
    public function role(): HasOneThrough{
        return $this->hasOneThrough(Trabajo::class, Role::class);
    }

    // public function puerta(): HasOneThrough{
    //     return $this->hasOneThrough(Puerta::class, Trabajo::class);
    // }

   
    
    


};

        