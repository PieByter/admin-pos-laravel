<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Permission;
use App\Models\ActivityLog;

class ProfileController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('dashboard')
                ->with('error', 'Data pengguna tidak ditemukan');
        }

        // Ambil permission user yang sedang login
        $userPermissions = [];
        $permissions = DB::table('user_permissions')
            ->join('permissions', 'permissions.id', '=', 'user_permissions.permission_id')
            ->where('user_permissions.user_id', $userId)
            ->select('permissions.name', 'permissions.display_name')
            ->get();

        foreach ($permissions as $perm) {
            // Tentukan tipe permission
            $type = 'full';
            if (str_ends_with($perm->name, '_read')) {
                $type = 'read';
            }

            $userPermissions[] = [
                'display_name' => $perm->display_name,
                'type' => $type
            ];
        }

        $userData = $user->toArray();
        $userData['permissions'] = $userPermissions;

        $data = [
            'title' => 'Profil Saya',
            'user' => $userData
        ];

        return view('profile.index', $data);
    }

    public function edit()
    {
        $userId = session('user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('dashboard')
                ->with('error', 'Data pengguna tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Profil',
            'user' => $user->toArray()
        ];

        return view('profile.edit', $data);
    }

    public function update(Request $request)
    {
        $userId = session('user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('profile.index')
                ->with('error', 'Data pengguna tidak ditemukan');
        }

        try {
            // Validasi dasar
            $rules = [
                'username' => 'required|min:4|unique:users,username,' . $userId,
                'email' => 'required|email|unique:users,email,' . $userId,
            ];

            $messages = [
                'username.required' => 'Username wajib diisi.',
                'username.min' => 'Username minimal 4 karakter.',
                'username.unique' => 'Username sudah digunakan, pilih username lain.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Email harus berupa email yang valid.',
                'email.unique' => 'Email sudah digunakan, pilih email lain.',
            ];

            // Validasi password jika diisi
            if ($request->filled('password')) {
                $rules['password'] = 'required|min:6|confirmed';
                $messages['password.required'] = 'Password wajib diisi.';
                $messages['password.min'] = 'Password minimal 6 karakter.';
                $messages['password.confirmed'] = 'Konfirmasi password tidak sama dengan password.';
            }

            // Validasi foto jika diupload
            if ($request->hasFile('photo')) {
                $rules['photo'] = 'image|mimes:jpeg,jpg,png|max:2048';
                $messages['photo.image'] = 'Foto profil harus berupa gambar.';
                $messages['photo.mimes'] = 'Foto profil harus jpg/jpeg/png.';
                $messages['photo.max'] = 'Foto profil maksimal 2MB.';
            }

            $request->validate($rules, $messages);

            // Handle upload foto
            $photoName = $user->photo;

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');

                // Buat nama file unik
                $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photoPath = 'uploads/profile/' . $photoName;

                // Simpan file asli
                $photo->storeAs('public/' . dirname($photoPath), basename($photoPath));

                // Kompres dan resize menggunakan GD Library
                $fullPath = storage_path('app/public/' . $photoPath);

                try {
                    $this->processImage($fullPath);
                } catch (\Exception $imageError) {
                    Log::error('Image processing error: ' . $imageError->getMessage());
                }

                // Hapus foto lama jika bukan default
                if ($user->photo && $user->photo !== 'default.jpg') {
                    Storage::delete('public/uploads/profile/' . $user->photo);
                }
            }

            // Siapkan data untuk update
            $updateData = [
                'username' => $request->username,
                'email' => strtolower($request->email),
                'updated_at' => now()
            ];

            // Update foto jika ada perubahan
            if ($photoName !== $user->photo) {
                $updateData['photo'] = $photoName;
            }

            // Update password jika diisi
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            // Update database
            $user->update($updateData);

            // Update session data
            $sessionData = [
                'username' => $updateData['username'],
                'email' => $updateData['email']
            ];

            if (isset($updateData['photo'])) {
                $sessionData['photo'] = $updateData['photo'];
            }

            session($sessionData);

            // Log aktivitas
            try {
                ActivityLog::create([
                    'user_id' => $userId,
                    'activity' => 'Mengubah profil',
                    'created_at' => now()
                ]);
            } catch (\Exception $logError) {
                Log::error('Error logging profile update: ' . $logError->getMessage());
            }

            return redirect()->route('profile.index')
                ->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    // Method untuk process image menggunakan GD Library
    private function processImage($imagePath)
    {
        $info = getimagesize($imagePath);
        $mime = $info['mime'];
        $maxSize = 2 * 1024 * 1024; // 2 MB
        $maxWidth = 1024;

        // Buat resource image berdasarkan tipe
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
                break;
            default:
                return; // Tipe tidak didukung
        }

        if (!$image) {
            return;
        }

        $width = imagesx($image);
        $height = imagesy($image);

        // Resize jika lebar > 1024px
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = intval($height * ($newWidth / $width));

            $resized = imagecreatetruecolor($newWidth, $newHeight);

            // Untuk PNG transparan
            if ($mime === 'image/png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                imagefill($resized, 0, 0, $transparent);
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        // Simpan dengan kompresi
        switch ($mime) {
            case 'image/jpeg':
                $quality = 70;
                do {
                    imagejpeg($image, $imagePath, $quality);
                    $fileSize = filesize($imagePath);
                    $quality -= 10;
                } while ($fileSize > $maxSize && $quality >= 30);
                break;
            case 'image/png':
                $compression = 7;
                do {
                    imagepng($image, $imagePath, $compression);
                    $fileSize = filesize($imagePath);
                    $compression++;
                } while ($fileSize > $maxSize && $compression <= 9);
                break;
            case 'image/gif':
                imagegif($image, $imagePath);
                break;
        }

        imagedestroy($image);
    }

    // Method untuk mengubah password saja
    public function changePassword(Request $request)
    {
        $userId = session('user_id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak sama',
        ]);

        // Cek password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Password saat ini salah'], 400);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Log aktivitas
        try {
            ActivityLog::create([
                'user_id' => $userId,
                'activity' => 'Mengubah password',
                'created_at' => now()
            ]);
        } catch (\Exception $logError) {
            Log::error('Error logging password change: ' . $logError->getMessage());
        }

        return response()->json(['success' => 'Password berhasil diubah']);
    }

    // Method untuk upload foto profil via AJAX
    public function uploadPhoto(Request $request)
    {
        $userId = session('user_id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        try {
            $photo = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photoPath = 'uploads/profile/' . $photoName;

            // Simpan file
            $photo->storeAs('public/' . dirname($photoPath), basename($photoPath));

            // Kompres dan resize menggunakan GD Library
            $fullPath = storage_path('app/public/' . $photoPath);
            $this->processImage($fullPath);

            // Hapus foto lama
            if ($user->photo && $user->photo !== 'default.jpg') {
                Storage::delete('public/uploads/profile/' . $user->photo);
            }

            // Update database
            $user->update(['photo' => $photoName]);

            // Update session
            session(['photo' => $photoName]);

            // Log aktivitas
            ActivityLog::create([
                'user_id' => $userId,
                'activity' => 'Mengubah foto profil',
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'photo_url' => asset('storage/uploads/profile/' . $photoName),
                'message' => 'Foto profil berhasil diubah'
            ]);
        } catch (\Exception $e) {
            Log::error('Photo upload error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengupload foto'], 500);
        }
    }

    // Method untuk hapus foto profil
    public function removePhoto()
    {
        $userId = session('user_id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }

        try {
            // Hapus foto lama jika bukan default
            if ($user->photo && $user->photo !== 'default.jpg') {
                Storage::delete('public/uploads/profile/' . $user->photo);
            }

            // Set ke default
            $user->update(['photo' => 'default.jpg']);
            session(['photo' => 'default.jpg']);

            // Log aktivitas
            ActivityLog::create([
                'user_id' => $userId,
                'activity' => 'Menghapus foto profil',
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'photo_url' => asset('storage/uploads/profile/default.jpg'),
                'message' => 'Foto profil berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Photo removal error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal menghapus foto'], 500);
        }
    }
}