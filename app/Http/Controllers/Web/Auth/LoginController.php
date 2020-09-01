<?php


namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('web.auth.login');
    }

    protected function guard()
    {
        return Auth::guard('web');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($request->expectsJson()) {
            return response()->json($user);
        }

        return redirect()->route('web.dashboard.home');
    }

    public function username()
    {
        $login = request()->input('npa');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'npa';
        request()->merge([$field => $login]);
        return $field;
    }
}
