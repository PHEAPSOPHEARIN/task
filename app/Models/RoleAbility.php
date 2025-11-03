<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAbility extends Model
{
    protected $fillable = [
       'role_id',
       'ability_id'
    ];

    public function ability()
    {
        return $this->belongsTo(Ability::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
