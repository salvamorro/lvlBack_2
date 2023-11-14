<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    // public  int $id;
    // public string $nombre;

    public function user(): HasMany{
        return $this->hasMany(User::class);
    }
}
