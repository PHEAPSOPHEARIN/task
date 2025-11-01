<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'status',
        'type'
    ];

    /**
     * Prevent deletion or update of 'default' type roles.
     * This protects essential system roles (e.g., the base 'customer' or 'admin' role).
     */
   protected static function boot()
    {
        parent::boot();
        static::updating(function ($role) {
            if ($role->type == 'default') {
                return false;
            }
        });
        static::deleting(function ($role) {
            if ($role->type == 'default') {
                return false;
            }
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'role_abilities');
    }
}