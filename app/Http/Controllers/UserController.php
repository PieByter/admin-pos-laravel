<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    // private function canRead(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('user', $permissions) || in_array('user_read', $permissions);
    // }

    // private function canWrite(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('user', $permissions);
    // }

    // private function requireReadAccess(): void
    // {
    //     if (!$this->canRead()) {
    //         abort(404, 'Access Denied');
    //     }
    // }

    // private function requireWriteAccess(): void
    // {
    //     if (!$this->canWrite()) {
    //         abort(404, 'Access Denied');
    //     }
    // }

    // private function getPermissionData(): array
    // {
    //     return [
    //         'can_read' => $this->canRead(),
    //         'can_write' => $this->canWrite()
    //     ];
    // }

    // Tampilkan daftar user
    public function index()
    {
        $users = User::with(['roles', 'permissions'])->orderBy('id')->get();

        $data = [
            'title' => 'Manajemen User',
            'users' => $users
        ];

        return view('users.index', $data);
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        $data = [
            'title' => 'Tambah User Baru',
            'roles' => $roles,
            'permissions' => $permissions
        ];

        return view('users.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:4|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'position' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:100',
            'division' => 'nullable|string|max:100',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'username' => $request->username,
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'position' => $request->position,
                'job_title' => $request->job_title,
                'division' => $request->division,
            ]);;

            // Assign roles
            if ($request->roles) {
                $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
                $user->assignRole($roleNames);
            }

            // Assign direct permissions (selain dari role)
            if ($request->permissions) {
                $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
                $user->givePermissionTo($permissionNames);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id() ?? session('user_id'),
                'activity' => 'Menambah user: ' . $request->username . ' (' . $request->email . ')',
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating user: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = User::with(['roles', 'permissions'])->find($id);
        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User tidak ditemukan');
        }

        $data = [
            'title' => 'Detail User',
            'user' => $user,
        ];

        return view('users.show', $data);
    }

    public function edit($id)
    {
        $user = User::with(['roles', 'permissions'])->find($id);
        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User tidak ditemukan');
        }

        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        // Get user's current role and permission IDs
        $userRoleIds = $user->roles->pluck('id')->toArray();
        $userPermissionIds = $user->permissions->pluck('id')->toArray();

        $data = [
            'title' => 'Edit Data User',
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'user_role_ids' => $userRoleIds,
            'user_permission_ids' => $userPermissionIds
        ];

        return view('users.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'username' => ['required', 'string', 'min:4', Rule::unique('users', 'username')->ignore($id)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'jabatan' => 'nullable|string|max:100',
            'bagian' => 'nullable|string|max:100',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // Update user data
            $userData = [
                'username' => $request->username,
                'email' => strtolower($request->email),
                'jabatan' => $request->jabatan,
                'bagian' => $request->bagian,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Sync roles (hapus semua role lama, assign role baru)
            $user->syncRoles([]);
            if ($request->roles) {
                $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
                $user->assignRole($roleNames);
            }

            // Sync permissions (hapus semua permission langsung lama, assign permission baru)
            $user->syncPermissions([]);
            if ($request->permissions) {
                $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
                $user->givePermissionTo($permissionNames);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id() ?? session('user_id'),
                'activity' => 'Mengupdate user: ' . $request->username . ' (' . $user->email . ')',
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating user: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User tidak ditemukan');
        }

        if ($id == (auth()->id() ?? session('user_id'))) {
            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun yang sedang digunakan');
        }

        try {
            DB::beginTransaction();

            // Check if user has related data
            $activityCount = ActivityLog::where('user_id', $id)->count();
            if ($activityCount > 0) {
                return redirect()->route('users.index')
                    ->with('error', 'User tidak dapat dihapus karena memiliki riwayat aktivitas');
            }

            $username = $user->username;
            $email = $user->email;

            // Hapus semua role dan permission user (otomatis oleh Spatie)
            $user->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id() ?? session('user_id'),
                'activity' => 'Menghapus user: ' . $username . ' (' . $email . ')',
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting user: ' . $e->getMessage());

            return redirect()->route('users.index')
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    // Method untuk check permission user
    public function checkPermission($permission)
    {
        $user = auth()->user();
        return $user ? $user->can($permission) : false;
    }

    // Method untuk check role user
    public function checkRole($role)
    {
        $user = auth()->user();
        return $user ? $user->hasRole($role) : false;
    }

    // Method untuk change password
    public function changePassword(Request $request, $id)
    {

        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
        }

        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        try {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengubah password user: ' . $user->username,
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah'
            ]);
        } catch (\Exception $e) {
            Log::error('Error changing password: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah password'
            ], 500);
        }
    }

    // Method untuk toggle status user
    public function toggleStatus($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
        }

        if ($id == session('user_id')) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat mengubah status akun sendiri'], 400);
        }

        try {
            $newStatus = $user->status === 'active' ? 'inactive' : 'active';
            $user->update(['status' => $newStatus]);

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
                'activity' => 'Mengubah status user: ' . $user->username . ' menjadi ' . $newStatus,
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status user berhasil diubah',
                'new_status' => $newStatus
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling user status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status'], 500);
        }
    }

    // Method untuk search users
    public function search(Request $request)
    {

        $query = $request->get('q', '');
        $users = User::where('username', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->orWhere('jabatan', 'LIKE', "%{$query}%")
            ->orWhere('bagian', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'username', 'email', 'jabatan', 'bagian', 'role']);

        return response()->json($users);
    }

    // Method untuk export users
    public function export()
    {;

        $users = User::orderBy('username')->get();

        $csv = "Username,Email,Jabatan,Bagian,Role,Status,Created At\n";
        foreach ($users as $user) {
            $csv .= '"' . $user->username . '",';
            $csv .= '"' . $user->email . '",';
            $csv .= '"' . ($user->jabatan ?? '') . '",';
            $csv .= '"' . ($user->bagian ?? '') . '",';
            $csv .= '"' . $user->role . '",';
            $csv .= '"' . ($user->status ?? 'active') . '",';
            $csv .= '"' . $user->created_at . '"';
            $csv .= "\n";
        }

        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }
}
