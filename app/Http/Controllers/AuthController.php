<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Mengimpor Hash Facade
use App\Models\Notifikasi;
class AuthController extends Controller
{
    // 1. Fungsi Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->with('error', 'Email atau Kata Sandi salah!');
    }

    // 2. Fungsi Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // 3. Fungsi Update Profil
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto
            'current_password' => 'required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            // Pesan Error Kustom (Opsional)
            'current_password.required_with' => 'Sandi saat ini wajib diisi jika Anda ingin mengganti sandi baru.',
            'password.confirmed' => 'Konfirmasi sandi baru tidak cocok.',
            'password.min' => 'Sandi baru minimal harus 8 karakter.'
        ]);
    

        $user->name = $request->name;
        $user->email = $request->email;

        // Proses Unggah Foto
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            // Simpan foto baru
            $path = $request->file('photo')->store('profiles', 'public');
            $user->photo = $path;
        }

        // Proses Update Password (sama seperti sebelumnya)
        if ($request->filled('current_password') || $request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Kata sandi saat ini tidak cocok.');
            }
            if (!$request->filled('password')) {
                return back()->with('error', 'Kata sandi baru tidak boleh kosong.');
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($request->filled('password')) {
            Notifikasi::create([
                'judul' => 'Kata Sandi Diperbarui',
                'pesan' => 'Kata sandi akun Administrator baru saja diubah untuk alasan keamanan.',
                'tipe'  => 'warning', // Kuning/Warning karena terkait keamanan akses
                'ikon'  => 'key'
            ]);
        } else {
            Notifikasi::create([
                'judul' => 'Profil Administrator Diperbarui',
                'pesan' => 'Perubahan pada nama akun atau foto profil berhasil disimpan.',
                'tipe'  => 'info', // Biru/Info untuk perubahan biasa
                'ikon'  => 'manage_accounts'
            ]);
        }

        return redirect()->route('pengaturan.index')->with('success', 'Profil berhasil diperbarui!');
    }
}
