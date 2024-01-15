<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Inc extends Model
{
    use HasFactory;
    protected $hidden = [
    
        "updated_at"
    ];
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function puerta(): BelongsTo{
        return $this->belongsTo(Puerta::class);
    }
    public function piso(): BelongsTo{
        return $this->belongsTo(Piso::class);
    }
    public function respuestas(): HasMany{
        return $this->hasMany(Respuesta::class);
    }
    public function userRespuesta(): HasOneThrough
    {
        return $this->hasOneThrough(Respuesta::class, user::class);
    }
}
