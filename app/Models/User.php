<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role_id',
        'company_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the user's direct permissions.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission($permission)
    {
        // Check direct permissions
        if ($this->permissions->contains('name', $permission)) {
            return true;
        }
        
        // Check role permissions
        if ($this->role && $this->role->permissions->contains('name', $permission)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get all permissions for this user (direct and from role)
     */
    public function getAllPermissions()
    {
        $rolePermissions = $this->role ? $this->role->permissions : collect([]);
        $directPermissions = $this->permissions;
        
        return $rolePermissions->merge($directPermissions)->unique('id');
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role)
    {
        return $this->role->name === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is employee
     */
    public function isEmployee()
    {
        return $this->hasRole('employee');
    }

    /**
     * Get the company that owns the user.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
