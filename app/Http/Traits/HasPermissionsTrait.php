<?php

namespace App\Http\Traits;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissionsTrait
{
    public function givePermissionTo(...$permissions)
    {
        $permissions = $this->getAllPermissions(Arr::flatten($permissions));

        dd($permissions);
        if ($permissions === null) {
            return $this;
        }

        $this->permissions()->saveMany($permissions);

        return $this;

    }

    public function withdrawPermissionTo(...$permissions)
    {
        $permissions = $this->getAllPermissions(Arr::flatten($permissions));

        $this->permissions()->detach($permissions);
        return $this;
    }

    public function updatePermission(...$permissions)
    {
        $this->permissions()->detach();
        return $this->givePermissionTo($permissions);
    }

    public function hasPermissionTo($permission)
    {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    protected function hasPermissionThroughRole($permission)
    {
        foreach ($permission->roles as $role) {
            if ($this->role->contain($role)) {
                return true;
            }

        }
        return false;

    }

    public function hasRole(...$roles)
    {
        // dd($roles);
        foreach ($roles as $role) {
            if($this->roles->contains('name', $role)) {
                return true;
            }
            return false;

        }
    }

    protected function getAllPermissions(array $permissions)
    {
        return Permission::whereIn('name', $permissions)->get();
    }

    protected function hasPermission($permission)
    {
        return (bool) $this->permissions->where('name', $permission->name)->count();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

}

