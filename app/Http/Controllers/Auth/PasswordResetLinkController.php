<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $status = Password::broker()->sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()
                ->with('status', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam Anda.');
        }

        return back()
            ->withErrors(['email' => $this->messageFor($status)])
            ->onlyInput('email');
    }

    private function messageFor(string $status): string
    {
        return match ($status) {
            Password::RESET_THROTTLED => 'Terlalu banyak permintaan. Silakan coba lagi dalam beberapa menit.',
            Password::RESET_LINK_SENT => 'Link reset password telah dikirim ke email Anda.',
            default => 'Email tersebut tidak terdaftar pada sistem kami.',
        };
    }
}
