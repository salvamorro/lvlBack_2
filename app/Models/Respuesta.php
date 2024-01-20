<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Respuesta extends Model
{

    protected $hidden = [
        "updated_at"
    ];
   public function inc(): BelongsTo{
    return $this->belongsTo(Inc::class);
   }

   public function user(): BelongsTo{  
      return $this->belongsTo(User::class);
   }
}
