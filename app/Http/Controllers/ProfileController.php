<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_picture')) {
            // Hapus foto profil lama dari S3 jika ada
            if ($user->profile_picture) {
                Storage::disk('s3')->delete($user->profile_picture);
            }

            // Upload foto profil baru ke S3
            $path = $request->file('profile_picture')->store('profile_pictures', 's3', 'public');
            Storage::disk('s3')->setVisibility($path, 'public');

            // Simpan path ke database
            $user->profile_picture = $path;
        }

        $user->save();

        return redirect()->back()->with('status', 'Profile updated successfully.');
    }
}
