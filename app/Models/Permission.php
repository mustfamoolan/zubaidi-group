<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'group',
    ];

    /**
     * The roles that belong to the permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }
    
    /**
     * Get the users that have this permission directly.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions');
    }

    /**
     * Get permissions grouped by group
     */
    public static function getGrouped()
    {
        return static::all()->groupBy('group');
    }
}
