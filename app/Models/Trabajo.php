<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Trabajo extends Model
{
    use HasFactory;

    public function venue():BelongsTo{
        return $this->belongsTo(Venue::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function role():BelongsTo{
        return $this->belongsTo(Role::class);
    }

    public function puerta():BelongsTo{
        return $this->belongsTo(Puerta::class);
    }
    // public function puerta(): HasOne{
    //     return $this->hasOne(Puerta::class);
    // }

    public function piso():BelongsTo{
        return $this->belongsTo(Piso::class);
    }
   
   
    
}
