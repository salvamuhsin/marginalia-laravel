<?php
namespace App\Http\Controllers;
use App\Models\{Order, User};
use Illuminate\Support\Facades\{Auth, Hash};

class AuthController extends Controller {
    public function loginForm() { return view('auth.login'); }
    public function registerForm() { return view('auth.register'); }

    public function login() {
        $c = request()->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($c, request()->boolean('remember'))) {
            request()->session()->regenerate();
            return redirect()->intended(auth()->user()->is_admin ? route('admin.dashboard') : route('home'));
        }
        return back()->withErrors(['email' => 'Wrong email or password.'])->onlyInput('email');
    }
    public function register() {
        $d = request()->validate(['name' => 'required|max:120', 'email' => 'required|email|unique:users', 'password' => 'required|min:8|confirmed']);
        $user = User::create(['name' => $d['name'], 'email' => $d['email'], 'password' => Hash::make($d['password'])]);
        Auth::login($user);
        request()->session()->regenerate();
        return redirect()->route('home');
    }
    public function logout() {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('home');
    }
    public function account() {
        return view('auth.account', ['orders' => Order::where('user_id', auth()->id())->with('items.product')->latest()->get()]);
    }
}
