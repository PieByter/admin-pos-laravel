<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Permission;
use App\Models\UserPermission;
use App\Models\ActivityLog;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function canRead(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('user', $permissions) || in_array('user_read', $permissions);
    }

    private function canWrite(): bool
    {
        $role = session('role');
        $permissions = session('permissions') ?? [];
        if ($role === 'superadmin') return true;
        return in_array('user', $permissions);
    }

    private function requireReadAccess(): void
    {
        if (!$this->canRead()) {
            abort(404, 'Access Denied');
        }
    }

    private function requireWriteAccess(): void
    {
        if (!$this->canWrite()) {
            abort(404, 'Access Denied');
        }
    }

    private function getPermissionData(): array
    {
        return [
            'can_read' => $this->canRead(),
            'can_write' => $this->canWrite()
        ];
    }

    // Tampilkan daftar user
    public function index()
    {
        $this->requireReadAccess();

        $users = DB::table('users')
            ->leftJoin('user_permissions', 'user_permissions.user_id', '=', 'users.id')
            ->leftJoin('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
            ->select('users.*')
            ->groupBy('users.id')
            ->orderBy('users.username')
            ->get()
            ->toArray();

        // Get permissions for each user
        foreach ($users as $i => $user) {
            $userPermissions = DB::table('user_permissions')
                ->leftJoin('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
                ->select('permissions.display_name')
                ->where('user_permissions.user_id', $user->id)
                ->get()
                ->toArray();

            $users[$i]->permissions = array_map(function ($perm) {
                return ['display_name' => $perm->display_name];
            }, $userPermissions);
        }

        $data = array_merge($this->getPermissionData(), [
            'title' => 'Manajemen User',
            'users' => $users
        ]);

        return view('users.index', $data);
    }

    // Tampilkan form tambah user
    public function create()
    {
        $this->requireWriteAccess();

        $permissions = Permission::orderBy('display_name')->get();

        $data = [
            'title' => 'Tambah User Baru',
            'permissions' => $permissions
        ];

        return view('users.create', $data);
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $this->requireWriteAccess();

        $request->validate([
            'username' => 'required|string|min:4|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'jabatan' => 'nullable|string|max:100',
            'bagian' => 'nullable|string|max:100',
            'role' => 'required|in:superadmin,admin,user',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ], [
            'username.required' => 'Username wajib diisi',
            'username.min' => 'Username minimal 4 karakter',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid'
        ]);

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'username' => $request->username,
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'jabatan' => $request->jabatan,
                'bagian' => $request->bagian,
                'role' => $request->role
            ]);

            // Handle permissions
            $allPermissions = Permission::all();
            $permissionNameById = $allPermissions->pluck('name', 'id')->toArray();

            if ($request->role === 'superadmin') {
                // Superadmin gets full access (no _read permissions)
                foreach ($allPermissions as $perm) {
                    if (strpos($perm->name, '_read') === false) {
                        UserPermission::create([
                            'user_id' => $user->id,
                            'permission_id' => $perm->id
                        ]);
                    }
                }
            } else {
                $permissions = $request->permissions ?? [];

                // Filter: if full permission exists, don't save read permission for same module
                $finalPermissions = [];
                $fullModules = [];

                foreach ($permissions as $permId) {
                    $permName = $permissionNameById[$permId] ?? '';
                    if ($permName && substr($permName, -5) !== '_read') {
                        $fullModules[] = $permName;
                    }
                }

                foreach ($permissions as $permId) {
                    $permName = $permissionNameById[$permId] ?? '';
                    if (substr($permName, -5) === '_read') {
                        $baseName = substr($permName, 0, -5);
                        if (in_array($baseName, $fullModules)) {
                            continue; // skip _read if full permission exists
                        }
                    }
                    $finalPermissions[] = $permId;
                }

                foreach ($finalPermissions as $permId) {
                    UserPermission::create([
                        'user_id' => $user->id,
                        'permission_id' => $permId
                    ]);
                }
            }

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
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

    // Tampilkan detail user
    public function show($id)
    {
        $this->requireReadAccess();

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User tidak ditemukan');
        }

        // Get user permissions
        $userPermissions = DB::table('user_permissions')
            ->leftJoin('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
            ->select('permissions.display_name')
            ->where('user_permissions.user_id', $user->id)
            ->get()
            ->map(function ($perm) {
                return ['display_name' => $perm->display_name];
            })
            ->toArray();

        $user->permissions = $userPermissions;

        $data = [
            'title' => 'Detail User',
            'user' => $user,
            'can_write' => $this->canWrite()
        ];

        return view('users.show', $data);
    }

    // Tampilkan form edit user
    public function edit($id)
    {
        $this->requireWriteAccess();

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User tidak ditemukan');
        }

        $permissions = Permission::orderBy('display_name')->get();

        // Get user permissions (permission IDs)
        $userPermissions = UserPermission::where('user_id', $id)
            ->pluck('permission_id')
            ->toArray();

        // Get user permission names untuk radio button
        $userPermissionNames = [];
        if (!empty($userPermissions)) {
            $userPermissionNames = Permission::whereIn('id', $userPermissions)
                ->pluck('name')
                ->toArray();
        }

        $data = [
            'title' => 'Edit Data User',
            'user' => $user,
            'permissions' => $permissions,
            'user_permissions' => $userPermissions,
            'user_permission_names' => $userPermissionNames
        ];

        return view('users.edit', $data);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $this->requireWriteAccess();

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
            'role' => 'required|in:superadmin,admin,user',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
        }

        $request->validate($rules, [
            'username.required' => 'Username wajib diisi',
            'username.min' => 'Username minimal 4 karakter',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password minimal 6 karakter',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid'
        ]);

        try {
            DB::beginTransaction();

            // Update user data
            $userData = [
                'username' => $request->username,
                'email' => strtolower($request->email),
                'jabatan' => $request->jabatan,
                'bagian' => $request->bagian,
                'role' => $request->role
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Delete existing permissions
            UserPermission::where('user_id', $id)->delete();

            // Handle new permissions
            $allPermissions = Permission::all();
            $permissionNameById = $allPermissions->pluck('name', 'id')->toArray();

            if ($request->role === 'superadmin') {
                // Superadmin gets full access (no _read permissions)
                foreach ($allPermissions as $perm) {
                    if (strpos($perm->name, '_read') === false) {
                        UserPermission::create([
                            'user_id' => $id,
                            'permission_id' => $perm->id
                        ]);
                    }
                }
            } else {
                $permissions = $request->permissions ?? [];

                // Filter: if full permission exists, don't save read permission for same module
                $finalPermissions = [];
                $fullModules = [];

                foreach ($permissions as $permId) {
                    $permName = $permissionNameById[$permId] ?? '';
                    if ($permName && substr($permName, -5) !== '_read') {
                        $fullModules[] = $permName;
                    }
                }

                foreach ($permissions as $permId) {
                    $permName = $permissionNameById[$permId] ?? '';
                    if (substr($permName, -5) === '_read') {
                        $baseName = substr($permName, 0, -5);
                        if (in_array($baseName, $fullModules)) {
                            continue; // skip _read if full permission exists
                        }
                    }
                    $finalPermissions[] = $permId;
                }

                foreach ($finalPermissions as $permId) {
                    UserPermission::create([
                        'user_id' => $id,
                        'permission_id' => $permId
                    ]);
                }
            }

            // Update session permissions if editing current user
            if ($id == session('user_id')) {
                $newPermissions = UserPermission::where('user_id', $id)
                    ->leftJoin('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
                    ->pluck('permissions.name')
                    ->toArray();
                session(['permissions' => $newPermissions]);
            }

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
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

    // Hapus user
    public function destroy($id)
    {
        $this->requireWriteAccess();

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User tidak ditemukan');
        }

        if ($id == session('user_id')) {
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

            // Delete user permissions first
            UserPermission::where('user_id', $id)->delete();

            // Delete user
            $user->delete();

            // Log activity
            ActivityLog::create([
                'user_id' => session('user_id'),
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

    // Method untuk change password
    public function changePassword(Request $request, $id)
    {
        $this->requireWriteAccess();

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
        $this->requireWriteAccess();

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
        $this->requireReadAccess();

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
    {
        $this->requireReadAccess();

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