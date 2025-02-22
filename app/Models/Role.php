<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    public function permissions()
    {
        return $this->hasMany(RolePermission::class)->select('id', 'module_action_id', 'role_id', 'is_active');
    }
}
