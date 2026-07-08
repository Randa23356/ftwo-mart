<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($username)
    {
        // Sementara gunakan name sebagai username sampai field username ditambahkan
        $user = User::where('name', $username)
            ->with(['ratings' => function($query) {
                $query->with('product')->with(['replies' => function($replyQuery) {
                    $replyQuery->with('user');
                }])->latest();
            }])
            ->firstOrFail();

        // Jika user yang login melihat profile sendiri, redirect ke edit profile
        if (auth()->check() && auth()->user()->id === $user->id) {
            return redirect()->route('profile.edit');
        }

        return view('users.profile', compact('user'));
    }
}
