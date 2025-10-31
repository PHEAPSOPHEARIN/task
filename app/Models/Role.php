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
    public function user(){
        return $this->belongsTo(User::class);
    }


}
