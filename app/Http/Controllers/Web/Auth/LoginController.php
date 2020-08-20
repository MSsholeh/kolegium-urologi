<?php


namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

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
}
