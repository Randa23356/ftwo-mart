<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request): \Illuminate\Contracts\View\View
    {
        return view("profile.edit", [
            "user" => $request->user(),
        ]);
    }

    /**
     * Update the user's profile photo.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            "photo" => ["required", "image", "mimes:jpg,jpeg,png", "max:1024"],
        ]);

        try {
            if ($request->hasFile("photo")) {
                // Delete old photo if it exists and it's not the default avatar
                if ($user->profile_photo_path && Storage::disk("public")->exists($user->profile_photo_path)) {
                    Storage::disk("public")->delete($user->profile_photo_path);
                }

                // Store the new photo with a unique name
                $file = $request->file("photo");
                $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs("avatars", $filename, "public");

                // Update the user's profile photo path
                $user->profile_photo_path = $path;
                $user->save();

                return back()->with("success", "Foto profil berhasil diperbarui.");
            }

            return back()->with("error", "Tidak ada file foto yang dipilih.");
        } catch (\Exception $e) {
            \Log::error('Profile photo upload error: ' . $e->getMessage());
            return back()->with("error", "Gagal mengupload foto profil. Silakan coba lagi.");
        }
    }

    /**
     * Update the user's profile information.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateInfo(
        Request $request,
    ): \Illuminate\Http\RedirectResponse {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "email",
                "max:255",
                "unique:users,email," . $user->id,
            ],
            "phone" => ["nullable", "string", "max:20"],
            "address" => ["nullable", "string", "max:500"],
            "bio" => ["nullable", "string", "max:500"],
            "birth_date" => ["nullable", "date", "before:today"],
            "gender" => ["nullable", "in:male,female,other"],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->bio = $request->bio;
        $user->birth_date = $request->birth_date;
        $user->gender = $request->gender;
        $user->save();

        return back()->with("success", "Informasi profil berhasil diperbarui.");
    }

    /**
     * Update the user's password.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(
        Request $request,
    ): \Illuminate\Http\RedirectResponse {
        $request->validate([
            "current_password" => ["required", "current_password"],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with("success", "Kata sandi berhasil diubah.");
    }

    /**
     * Show the user's profile.
     *
     * @param string $slug
     * @return \Illuminate\Contracts\View\View
     */
    public function show(string $slug): \Illuminate\Contracts\View\View
    {
        // Find user by slug or fallback to ID if slug starts with 'user-'
        if (str_starts_with($slug, 'user-')) {
            $userId = str_replace('user-', '', $slug);
            $user = User::findOrFail($userId);
        } else {
            $user = User::where('slug', $slug)->firstOrFail();
        }
        
        // Load relationships for better performance
        $user->load(["orders", "conversations", "messages"]);

        return view("profile.show", compact("user"));
    }
}
