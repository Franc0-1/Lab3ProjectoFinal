<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Filtro por rol
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filtro por búsqueda
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Update user role.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        // Remover roles existentes
        $user->syncRoles([]);
        
        // Asignar nuevo rol
        $user->assignRole($request->role);

        return redirect()->back()
            ->with('success', 'Rol de usuario actualizado exitosamente.');
    }

    /**
     * Show user statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::role('admin')->count(),
            'employee_users' => User::role('employee')->count(),
            'customer_users' => User::role('customer')->count(),
            'recent_registrations' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.users.statistics', compact('stats'));
    }
}
