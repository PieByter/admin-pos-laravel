<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogController extends Controller
{
    // private function canRead(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('activity_logs', $permissions) || in_array('activity_logs_view', $permissions);
    // }

    // private function canWrite(): bool
    // {
    //     $role = session('role');
    //     $permissions = session('permissions') ?? [];
    //     if ($role === 'superadmin') return true;
    //     return in_array('activity_logs', $permissions);
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

    public function index()
    {

        $logs = DB::table('activity_logs')
            ->leftJoin('users', 'users.id', '=', 'activity_logs.user_id')
            ->select('activity_logs.*', 'users.username')
            ->orderBy('activity_logs.created_at', 'DESC')
            ->get();

        $data =  [
            'logs' => $logs,
            'title' => 'Log Activity',
        ];

        return view('activity_logs.index', $data);
    }

    public function create()
    {
        $search = request()->get('search');
        $page = request()->get('page');
        $users = User::select('id', 'username')->orderBy('id')->get();

        return view('activity_logs.create', [
            'title' => 'Tambah Logs Aktivitas',
            'users' => $users,
            'search' => $search,
            'page' => $page
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'activity' => 'required|string|max:255'
        ], [
            'user_id.required' => 'User wajib dipilih',
            'user_id.exists' => 'User tidak valid',
            'activity.required' => 'Aktivitas wajib diisi',
            'activity.max' => 'Aktivitas maksimal 255 karakter'
        ]);

        ActivityLog::create([
            'user_id' => $request->user_id,
            'activity' => $request->activity,
            'created_at' => now()
        ]);

        return redirect()->route('superadmin.activity-logs.index')
            ->with('success', 'Log aktivitas berhasil ditambahkan');
    }

    public function show($id)
    {

        $log = DB::table('activity_logs')
            ->leftJoin('users', 'users.id', '=', 'activity_logs.user_id')
            ->select('activity_logs.*', 'users.username', 'users.email')
            ->where('activity_logs.id', $id)
            ->first();

        if (!$log) {
            return redirect()->route('superadmin.activity-logs.index')
                ->with('error', 'Log tidak ditemukan');
        }

        $data = [
            'log' => $log,
            'title' => 'Detail Log Aktivitas'
        ];

        return view('activity_logs.show', $data);
    }

    public function edit($id)
    {

        $log = ActivityLog::find($id);
        if (!$log) {
            return redirect()->route('superadmin.activity-logs.index')
                ->with('error', 'Log tidak ditemukan');
        }

        $users = User::select('id', 'username')->orderBy('username')->get()->toArray();

        return view('activity_logs.edit', [
            'log' => $log->toArray(),
            'users' => $users,
            'title' => 'Edit Logs Aktivitas',
        ]);
    }

    public function update(Request $request, $id)
    {

        $log = ActivityLog::find($id);
        if (!$log) {
            return redirect()->route('superadmin.activity-logs.index')
                ->with('error', 'Log tidak ditemukan');
        }

        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'activity' => 'required|string|max:255'
        ], [
            'user_id.required' => 'User wajib dipilih',
            'user_id.exists' => 'User tidak valid',
            'activity.required' => 'Aktivitas wajib diisi',
            'activity.max' => 'Aktivitas maksimal 255 karakter'
        ]);

        $log->update([
            'user_id' => $request->user_id,
            'activity' => $request->activity,
            'updated_at' => now()
        ]);

        return redirect()->route('superadmin.activity-logs.index')
            ->with('success', 'Log aktivitas berhasil diupdate');
    }

    public function destroy($id)
    {

        $log = ActivityLog::find($id);
        if (!$log) {
            return redirect()->route('superadmin.activity-logs.index')
                ->with('error', 'Log tidak ditemukan');
        }

        $log->delete();

        return redirect()->route('superadmin.activity-logs.index')
            ->with('success', 'Log aktivitas berhasil dihapus');
    }

    // Method tambahan untuk bulk delete (opsional)
    public function bulkDelete(Request $request)
    {

        $request->validate([
            'log_ids' => 'required|array',
            'log_ids.*' => 'integer|exists:activity_logs,id'
        ]);

        ActivityLog::whereIn('id', $request->log_ids)->delete();

        return redirect()->route('superadmin.activity-logs.index')
            ->with('success', 'Log aktivitas terpilih berhasil dihapus');
    }

    // Method untuk clear semua logs (opsional)
    public function clearAll()
    {

        $count = ActivityLog::count();
        ActivityLog::truncate();

        return redirect()->route('superadmin.activity-logs.index')
            ->with('success', "Semua log aktivitas ({$count} data) berhasil dihapus");
    }

    // Method untuk filter logs berdasarkan tanggal
    public function filterByDate(Request $request)
    {

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $userId = $request->user_id;

        $query = DB::table('activity_logs')
            ->leftJoin('users', 'users.id', '=', 'activity_logs.user_id')
            ->select('activity_logs.*', 'users.username');

        if ($startDate) {
            $query->whereDate('activity_logs.created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('activity_logs.created_at', '<=', $endDate);
        }

        if ($userId) {
            $query->where('activity_logs.user_id', $userId);
        }

        $logs = $query->orderBy('activity_logs.created_at', 'DESC')->get()->toArray();
        $users = User::select('id', 'username')->orderBy('username')->get()->toArray();

        $data = [
            'logs' => $logs,
            'users' => $users,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'user_id' => $userId
            ],
            'title' => 'Log Activity (Filtered)',
        ];

        return view('superadmin.activity-logs.index', $data);
    }
}
