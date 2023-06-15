<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Silakan isi username terlebih dahulu',
            'password.required' => 'Silakan isi password terlebih dahulu'
        ]);

        $credentials = $request->only('username', 'password');
        $user = User::where('email', $credentials['username'])
            ->orWhere('no_telepon', $credentials['username'])
            ->first();

        if (!$user) {
            return redirect()->back()->with(['message' => 'Username tidak tersedia.']);
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::loginUsingId($user->id);
            if ($user->type == 'Pasien') {
                return redirect()->intended();
            } elseif ($user->type == 'Perawat') {
                return redirect()->route('dashboard.index');
            } elseif ($user->type == 'Dokter') {
                return redirect()->route('dashboard.index');
            } elseif ($user->type == 'Administrator') {
                return redirect()->route('dashboard.index');
            } else {
                return redirect()->intended();
            }
        } else {
            return redirect()->back()->with(['message' => 'Password salah.']);
        }
    }

    public function username()
    {
        $fieldType = filter_var(request()->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'no_telepon';
        request()->merge([$fieldType => request('username')]);
        return $fieldType;
    }
}
