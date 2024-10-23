<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Method untuk menampilkan form profile (Jika diperlukan)
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    // Method untuk menangani update profile
    public function update(Request $request)
    {
        // Validasi gambar
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Ambil user yang sedang login
        $user = Auth::user();

        // Jika ada file yang diupload
        if ($request->hasFile('profile_picture')) {
            // Hapus foto profil lama dari S3 jika ada
            if ($user->profile_picture) {
                Storage::disk('s3')->delete($user->profile_picture);
            }

            // Upload foto profil baru ke S3
            $path = $request->file('profile_picture')->store('profile_pictures', 's3');

            // Set visibilitas file di S3 menjadi public
            Storage::disk('s3')->setVisibility($path, 'public');

            // Simpan path foto profil ke database
            $user->profile_picture = $path;
        }

        // Simpan perubahan profil lainnya jika ada
        $user->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('status', 'Profile updated successfully.');
    }
}
