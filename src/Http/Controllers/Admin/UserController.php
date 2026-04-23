<?php

namespace Acme\CmsDashboard\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use App\Models\User;
use Acme\CmsDashboard\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403, 'You do not have permission to manage users.');
        }
        $query = User::with('role');

        if ($request->filled('s')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->s . '%')
                  ->orWhere('email', 'like', '%' . $request->s . '%')
                  ->orWhere('username', 'like', '%' . $request->s . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('slug', $request->role);
            });
        }

        if ($request->status === 'blocked') {
            $query->where(function($q) {
                $q->where('is_blocked', true)
                  ->orWhere(function($sq) {
                      $sq->whereNotNull('blocked_until')
                         ->where('blocked_until', '>', now());
                  });
            });
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        
        $allCount = User::count();
        $adminCount = User::whereHas('role', function($q){ $q->where('slug', 'administrator'); })->count();
        $editorCount = User::whereHas('role', function($q){ $q->where('slug', 'editor'); })->count();
        $authorCount = User::whereHas('role', function($q){ $q->where('slug', 'author'); })->count();
        $subscriberCount = User::whereHas('role', function($q){ $q->where('slug', 'subscriber'); })->count();
        $blockedCount = User::where('is_blocked', true)
            ->orWhere(function($q) {
                $q->whereNotNull('blocked_until')
                  ->where('blocked_until', '>', now());
            })->count();
        
        return view('cms-dashboard::admin.users.index', compact(
            'users', 'allCount', 'adminCount', 'editorCount', 'authorCount', 'subscriberCount', 'blockedCount'
        ));
    }

    public function create()
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403);
        }
        $roles = Role::orderBy('name')->get();
        return view('cms-dashboard::admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403);
        }
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('cms-dashboard::admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403);
        }
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function toggleBlock(User $user)
    {
        if (!auth()->user()->hasPermission('manage_users')) {
            abort(403);
        }
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot block your own account.');
        }

        // If user is temporarily blocked, or permanently blocked, we toggle it
        $isCurrentlyBlocked = $user->is_blocked || ($user->blocked_until && $user->blocked_until->isFuture());

        if ($isCurrentlyBlocked) {
            // Unblock
            $user->is_blocked = false;
            $user->login_attempts = 0;
            $user->blocked_until = null;
            $user->last_failed_login_ip = null;
            $user->save();
            return redirect()->route('admin.users.index')->with('success', "User has been unblocked successfully.");
        } else {
            // Block
            $user->is_blocked = true;
            $user->save();
            return redirect()->route('admin.users.index')->with('success', "User has been blocked successfully.");
        }
    }
}
