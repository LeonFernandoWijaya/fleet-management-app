<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use function PHPSTORM_META\map;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public static function createRecord($data)
    {
        return self::create($data);
    }

    public static function updateRecord($id, $data)
    {
        return self::where('id', $id)->update($data);
    }

    public function hasModuleAction(string $moduleName, string $action)
    {
        $moduleId = Module::where('name', $moduleName)->first()->id;
        $actionId = Action::where('name', $action)->first()->id;

        if ($moduleId && $actionId) {
            $moduleActionId = ModuleAction::where('module_id', $moduleId)->where('action_id', $actionId)->first()->id;
            return RolePermission::where('role_id', $this->role_id)->where('module_action_id', $moduleActionId)->pluck('is_active')->first();
        } else {
            return false;
        }
    }
}
