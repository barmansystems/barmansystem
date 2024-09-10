<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Rules\GoogleCaptcha;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/panel';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'phone';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => ['required', new GoogleCaptcha()],
        ]);
    }

    public function showLoginForm()
    {
        $role = \request()->role;
        return view('auth.login', compact('role'));
    }

    protected function attemptLogin(Request $request)
    {
        $remember = $request->boolean('remember');
        $attemp = $this->guard()->attempt(
            $this->credentials($request), $remember
        );

        if ($attemp) {
            $user = $this->guard()->user();

            if ($user->role->name == $request->role || ($user->role->name == 'admin' && ($request->role == 'ceo' || $request->role == null))) {
                return $attemp;
            } else {
                $this->guard()->logout();

                $request->validate([
                    'notAllow' => 'required'
                ], [
                    'notAllow.required' => 'شما به این بخش دسترسی ندارید!'
                ]);

                return false;
            }
        }

        return $attemp;
    }

    public function logout(Request $request)
    {
        $role = $this->guard()->user()->role->name;

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect("/login?role=$role");
    }
}
