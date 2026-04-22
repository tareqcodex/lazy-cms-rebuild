<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Acme\CmsDashboard\Models\Role;
use Acme\CmsDashboard\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')
            ->orderByRaw("CASE WHEN slug = 'super-admin' THEN 0 WHEN slug = 'administrator' THEN 1 ELSE 2 END")
            ->orderBy('name')
            ->get();
        return view('cms-dashboard::admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $data = $this->getPermissionsWithCpts();
        return view('cms-dashboard::admin.roles.create', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:roles,slug',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array'
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $role = Role::create($validated);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        // Hierarchical protection: Only Super Admin can edit Super Admin
        if ($role->slug === 'super-admin' && !auth()->user()->hasRole('super-admin')) {
            return redirect()->route('admin.roles.index')->with('error', 'Only Super Admin can manage Super Admin role.');
        }

        $data = $this->getPermissionsWithCpts();
        $data['role'] = $role;
        
        if ($role->slug === 'super-admin') {
            $data['rolePermissions'] = Permission::pluck('id')->toArray();
        } else {
            $data['rolePermissions'] = $role->permissions->pluck('id')->toArray();
        }
        
        return view('cms-dashboard::admin.roles.edit', $data);
    }

    protected function getPermissionsWithCpts()
    {
        // 1. Ensure CPT permissions exist
        try {
            $cpts = \Acme\CmsDashboard\Models\PostType::where('is_builtin', false)->get();
            foreach ($cpts as $cpt) {
                Permission::firstOrCreate(
                    ['slug' => 'manage_' . $cpt->slug],
                    [
                        'name' => 'Manage ' . $cpt->name,
                        'description' => 'Manage content for ' . $cpt->name
                    ]
                );
            }
        } catch (\Exception $e) {}

        $allPermissions = Permission::all();
        
        return [
            'corePermissions' => $allPermissions->filter(fn($p) => !str_starts_with($p->slug, 'manage_')),
            'cptPermissions'  => $allPermissions->filter(fn($p) => str_starts_with($p->slug, 'manage_')),
        ];
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'nullable|array'
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $role->update($validated);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        // Protect system roles
        if (in_array($role->slug, ['administrator', 'super-admin', 'subscriber'])) {
            return redirect()->route('admin.roles.index')->with('error', 'Cannot delete system roles.');
        }

        // Only super-admin can delete roles if needed (though system roles are still blocked)
        if (!auth()->user()->hasRole('super-admin') && in_array($role->slug, ['administrator', 'super-admin'])) {
             return redirect()->route('admin.roles.index')->with('error', 'Unauthorized.');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
