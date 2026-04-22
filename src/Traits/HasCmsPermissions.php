<?php

namespace Acme\CmsDashboard\Traits;

trait HasCmsPermissions
{
    public function role()
    {
        return $this->belongsTo(\Acme\CmsDashboard\Models\Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role && $this->role->slug === $role;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()->where('slug', $permission)->exists();
    }

    public function hasCmsPermission(string $permission): bool
    {
        return $this->hasPermission($permission);
    }
}
