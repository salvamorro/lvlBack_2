<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Piso extends Model
{
  // public function puertas(): HasMany{
  //   return $this->hasMany(Puerta::class);
  // }
  public function venue(): BelongsTo{
    return $this->belongsTo(Venue::class);
  }

 
 
}
