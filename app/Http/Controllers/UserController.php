<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNotNull('email_verified_at')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('users.index', compact('users'));
    }

    public function show($userId)
    {
        $user = User::findOrFail($userId);

        return view('users.show', compact('user'));
    }

    public function check_create($name, $email)
    {
        $user = User::firstOrCreate([
            'name' => $name,
            'email' => $email
        ], [
            'password' => Hash::make(Str::random(10))
        ]);

        return view('users.show', compact('user'));
    }

    public function check_update($name, $email)
    {
        $user = User::updateOrCreate([
            'name' => $name
        ], [
            'email' => $email,
            'password' => Hash::make(Str::random(10))
        ]); // updated or created user

        return view('users.show', compact('user'));
    }

    public function destroy(Request $request)
    {
        $users = User::whereIn('id', $request->users)->delete();

        return redirect('/')->with('success', 'Users deleted');
    }

    public function only_active()
    {
        $users = User::active()->get();

        return view('users.index', compact('users'));
    }

}
