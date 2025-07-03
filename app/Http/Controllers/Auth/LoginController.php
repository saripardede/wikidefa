<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // app/Http/Controllers/Auth/LoginController.php

    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'teknisi') {
            return redirect()->route('user.dashboard'); 
        }        
    }

    // Form Login untuk User Biasa
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Login User Biasa
   public function login(Request $request)
{
    $request->validate([
        'login' => 'required|string',
        'password' => 'required|string',
    ]);

    $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    $credentials = [
        $loginType => $request->login,
        'password' => $request->password
    ];

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        User::where('id', $user->id)->update([
            'last_login' => now()
        ]);

        if ($user->id === 1 || $user->is_approved) {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.index');
            }
        } else {
            Auth::logout();
            return redirect('/login')->with('not_approved', 'Akun Anda belum disetujui oleh admin.');
        }
    }

    return back()->withErrors([
        'login' => 'Email/Username atau password salah.',
    ]);
}

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
