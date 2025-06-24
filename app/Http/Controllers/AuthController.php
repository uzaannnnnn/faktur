<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = User::find(Auth::id());

            // Cek apakah status sudah block
            if ($user->status === 'block') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah diblokir.',
                ]);
            }

            // Cek tagihan & jatuh tempo
            if ($user->tagihan > 0 && $user->jatuh_tempo) {
                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($user->jatuh_tempo), false);

                if ($daysLeft < 30) {
                    $user->status = 'block';
                    $user->save();

                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Akun Anda diblokir karena memiliki tagihan yang mendekati jatuh tempo.',
                    ]);
                }
            }

            return redirect()->intended("/{$user->role}/dashboard");
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
