<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Puerta extends Model
{
  public function piso():BelongsTo{
    return $this->belongsTo(Piso::class);
  }
  // public function users(): BelongsToMany{
  //   return $this->hasMany(User::class);
  // }
  public function users(): HasManyThrough{
    return $this->hasManyThrough( User::class, Trabajo::class);
  }
 
}